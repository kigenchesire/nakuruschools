<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', User::class);

        $search = trim((string) $request->query('q'));

        $users = User::query()
            ->when($search !== '', fn ($q) => $q->where(fn ($q) => $q
                ->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")))
            ->when(in_array($request->query('status'), [User::STATUS_ACTIVE, User::STATUS_INACTIVE], true), fn ($q) => $q->where('status', $request->query('status')))
            ->orderBy('first_name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'search'));
    }

    public function create(): View
    {
        $this->authorize('create', User::class);

        return view('admin.users.form', ['user' => new User(['status' => User::STATUS_ACTIVE])]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $this->authorize('create', User::class);

        // The "hashed" cast on the model hashes the password before it is stored.
        User::create($request->validated());

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        $this->authorize('update', $user);

        return view('admin.users.form', compact('user'));
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        $data = $request->validated();

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        if ($request->user()->is($user) && $data['status'] !== User::STATUS_ACTIVE) {
            return back()->withInput()->withErrors(['status' => 'You cannot deactivate your own account.']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', isset($data['password'])
            ? 'User updated and password reset successfully.'
            : 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }

    public function toggle(User $user): RedirectResponse
    {
        $this->authorize('toggle', $user);

        $user->update(['status' => $user->isActive() ? User::STATUS_INACTIVE : User::STATUS_ACTIVE]);

        return back()->with('success', $user->full_name . ($user->isActive() ? ' activated.' : ' deactivated.'));
    }
}
