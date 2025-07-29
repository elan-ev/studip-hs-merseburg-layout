<?php

namespace ElanEv\StudipLayoutHsMerseburg;

// this class first tries to find a patch template in its patch path
class PatchTemplateFactory extends \Flexi_TemplateFactory
{
    public function __construct(private \Flexi_TemplateFactory $core_factory, string $path)
    {
        parent::__construct($path);
    }

    /**
     * This method behaves as usual but prefers files from the "patch"
     * location over the "core" files of Stud.IP.
     *
     * @param  string     a template string
     *
     * @return string     an absolute filename
     *
     * @throws Flexi_TemplateNotFoundException  if the template could not be found
     */
    public function get_template_file(string $template0): string
    {
        try {
            return parent::get_template_file($template0);
        } catch (\Flexi_TemplateNotFoundException $e) {
            return $this->core_factory->get_template_file($template0);
        }
    }
}
