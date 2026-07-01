<?php

/**
 * @author Sergey Tevs
 * @email sergey@tevs.org
 */

namespace Modules\Main\Console;

use Core\Console\Command;
use Modules\Main\MainTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Minify extends Command {

    use MainTrait;

    public function __construct($application) {
        parent::__construct($application);
    }

    protected function configure(): void {
        $this->setName('main:minify')
            ->setDescription("Concatenate CSS and JS from template config.php into app.min bundles")
            ->addArgument('template', InputArgument::OPTIONAL, 'Template name (default: from config.ini)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        $config = $this->getContainer()->get('config')->getSetting('template');

        $templateName = $input->getArgument('template') ?? $config['name'];
        $templateDir  = ROOT_DIR . $config['path'] . DIRECTORY_SEPARATOR . $templateName;
        $configFile   = $templateDir . DIRECTORY_SEPARATOR . 'config.php';

        if (!file_exists($configFile)) {
            $output->writeln("<error>config.php not found: $configFile</error>");
            return self::FAILURE;
        }

        $bundleConfig = require $configFile;
        $wwwDir = ROOT_DIR . 'www';

        $this->buildBundle($bundleConfig['js'],  $templateDir, $wwwDir . $bundleConfig['output']['js'],  'JS',  $output);
        $this->buildBundle($bundleConfig['css'], $templateDir, $wwwDir . $bundleConfig['output']['css'], 'CSS', $output);

        return self::SUCCESS;
    }

    private function buildBundle(array $files, string $templateDir, string $outputPath, string $type, OutputInterface $output): void {
        $content = '';
        $missing = [];

        foreach ($files as $file) {
            $fullPath = $templateDir . $file;
            if (!file_exists($fullPath)) {
                $missing[] = $file;
                continue;
            }
            $content .= file_get_contents($fullPath) . "\n";
        }

        if ($missing) {
            foreach ($missing as $f) {
                $output->writeln("<comment>  [SKIP] $f not found</comment>");
            }
        }

        file_put_contents($outputPath, $content);

        $kb = round(strlen($content) / 1024);
        $output->writeln("<info>  [$type] $outputPath ({$kb} KB)</info>");
    }

}