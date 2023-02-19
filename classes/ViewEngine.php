<?php

namespace Classes;

use Exception;

final class ViewEngine
{

    /** @var string|null content contains the HTML of the view. Is `null` if the function `build()` has not been called yet */
    private ?string $content;

    /** @param string $fileName is the name of the view to search. Can also be a path if view is in a subfolder */
    public function __construct(
        private string $fileName
    ) {
        $this->fileName = $fileName;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Searches the views directory for the requested view and applies components
     * @throws Exception if the view file cannot be found
     */
    public function build(): string
    {
        $viewFilePath = __DIR__ . "/../views/{$this->fileName}.html";

        if (!file_exists($viewFilePath)) {
            throw new Exception("View file for {$this->fileName} does not exist");
        }

        $this->content = file_get_contents($viewFilePath);

        $this->applyComponents();

        return $this->content;
    }

    /**
     * Echoes the view to the page
     */
    public function view(): void
    {
        echo $this->content;
    }

    /**
     * Automatically calls the `build()` and `view()` functions
     */
    public function buildAndView(): void
    {
        $this->build();
        $this->view();
    }

    /**
     * Automatically searches for HTML components to inject into the view
     * @throws Exception if a required component cannot be found
     */
    private function applyComponents(): void
    {
        preg_match_all('/<app-[a-z]+>|<\/app-[a-z]+>/', $this->content, $componentsUsedByView);

        foreach ($componentsUsedByView[0] as $component) {
            // If it's the closing tag
            if (str_starts_with($component, '</')) {
                // Simply remove it from the page
                $this->content = str_replace($component, '', $this->content);
            } else {
                $componentFileName = str_replace('<app-', '', $component);
                $componentFileName = str_replace('>', '', $componentFileName);
                $componentFilePath = __DIR__ . "/../components/{$componentFileName}.html";

                if (!file_exists($componentFilePath)) {
                    throw new Exception("Component file for {$componentFileName} in {$this->fileName} does not exist");
                }

                $this->content = str_replace($component, file_get_contents($componentFilePath), $this->content);
            }
        }
    }
}
