<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use App\Controller\MerchantController;

#[AsCommand(name: 'kazidomi:merchant', description: 'Manage merchants')]
class KazidomiMerchantCommand extends Command {

    private $merchantController;

    public function __construct(MerchantController $merchantController) {
        parent::__construct();
        $this->merchantController = $merchantController;
    }

    protected function configure(): void {
        $this->setDescription('Manage merchants')
                ->addArgument('action', InputArgument::OPTIONAL, 'The action to perform: create, list, or fetch')
                ->addArgument('param', InputArgument::OPTIONAL, 'Name for create or ID for fetch');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        
        $action = $input->getArgument('action');
        $param = $input->getArgument('param');
        $helper = $this->getHelper('question');
        
        // check if action is not empty and is one of possible choice
        while (!$action || !in_array($action, ['create', 'list', 'fetch'])) {
            $question = new ChoiceQuestion(
                'Please choose an action (create, list, fetch): ',
                ['create', 'list', 'fetch']
            );
            $action = $helper->ask($input, $output, $question);
            if (!$action || !in_array($action, ['create', 'list', 'fetch'])) {
                $output->writeln("Invalid action. Please enter 'create', 'list', or 'fetch'.\n");
            }
        }

        try {
            switch ($action) {
                case 'create':
                    // check if param is not empty
                    while (!$param) {
                        $question = new Question('Please enter the name of the merchant: ');
                        $param = $helper->ask($input, $output, $question);
                        if (!$param) {
                            $output->writeln("Name cannot be empty. Please enter a valid name.\n");
                        }
                    }
                    $result = $this->merchantController->createMerchant((string) $param);
                    $output->writeln($result);
                    break;
                case 'list':
                    $merchants = $this->merchantController->listMerchants();
                    if(count($merchants) > 0) {
                        foreach ($merchants as $merchant) {
                            $output->writeln('ID: ' . $merchant['id'] . ', Name: ' . $merchant['name']);
                            $output->writeln('');
                        }
                    } else {
                        $output->writeln('No merchants found');
                    }
                    
                    break;
                case 'fetch':
                    // check if param is not empty and id number
                    while (!$param || !filter_var($param, FILTER_VALIDATE_INT)) {
                        $question = new Question('Please enter the ID of the merchant: ');
                        $param = $helper->ask($input, $output, $question);
                        if (!$param || !filter_var($param, FILTER_VALIDATE_INT)) {
                            $output->writeln("ID cannot be empty and it must be an integer. Please enter a valid ID.\n");
                        }
                    }
                    $result = $this->merchantController->getMerchant((int) $param);
                    $output->writeln($result);
                    break;
                default:
                    $output->writeln('Invalid action. Use create, list, or fetch.');
            }
        } catch (\Exception $e) {
            $output->writeln('Error: ' . $e->getMessage());
        }

        return Command::SUCCESS;
    }

}
