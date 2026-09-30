<?php

namespace Tests\Unit;

use App\Services\HtmlSanitizer;
use PHPUnit\Framework\TestCase;

class HtmlSanitizerTest extends TestCase
{
    public function test_it_keeps_editor_formatting(): void
    {
        $html = '<h2>Title</h2><p><strong>Bold</strong> and <a href="https://example.com" target="_blank">link</a></p><ul><li>One</li></ul>';

        $clean = (new HtmlSanitizer())->clean($html);

        $this->assertStringContainsString('<h2>Title</h2>', $clean);
        $this->assertStringContainsString('<strong>Bold</strong>', $clean);
        $this->assertStringContainsString('rel="noopener noreferrer"', $clean);
        $this->assertStringContainsString('<li>One</li>', $clean);
    }

    public function test_it_strips_dangerous_markup(): void
    {
        $html = '<p style="x" onmouseover="evil()">Hi</p><script>alert(1)</script><iframe src="https://x"></iframe>'
            . '<img src="javascript:alert(1)" onerror="evil()"><a href=" JaVaScRiPt:alert(1)">x</a><svg><script>1</script></svg>';

        $clean = (new HtmlSanitizer())->clean($html);

        foreach (['script', 'onmouseover', 'onerror', 'iframe', 'javascript', 'style=', 'svg'] as $needle) {
            $this->assertStringNotContainsStringIgnoringCase($needle, $clean);
        }
        $this->assertStringContainsString('<p>Hi</p>', $clean);
    }

    public function test_it_handles_utf8_and_empty_input(): void
    {
        $sanitizer = new HtmlSanitizer();

        $this->assertSame('', $sanitizer->clean(null));
        $this->assertSame('<p>Karibu — “welcome”</p>', $sanitizer->clean('<p>Karibu — “welcome”</p>'));
    }
}
