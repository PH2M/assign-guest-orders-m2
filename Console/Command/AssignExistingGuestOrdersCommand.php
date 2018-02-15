<?php
/**
 * 2011-2018 PH2M
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0) that is available
 * through the world-wide-web at this URL: http://www.opensource.org/licenses/OSL-3.0
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to contact@ph2m.com so we can send you a copy immediately.
 *
 * @author PH2M - contact@ph2m.com
 * @copyright 2011-2018 PH2M
 * @license http://www.opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */
namespace PH2M\AssignGuestOrders\Console\Command;

use Magento\Framework\App\State;
use Magento\Framework\Console\Cli;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PH2M\AssignGuestOrders\Model\Order;

/**
 * Class AssignExistingGuestOrdersCommand
 * @package PH2M\AssignGuestOrders\Console\Command
 */
class AssignExistingGuestOrdersCommand extends Command
{
    /**
     * @var State
     */
    protected $appState;

    /**
     * @var Order
     */
    private $order;

    /**
     * AssignExistingGuestOrdersCommand constructor.
     * @param State $appState
     * @param Order $order
     */
    public function __construct(
        State $appState,
        Order $order
    ) {
        $this->appState = $appState;
        $this->order    = $order;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('ph2m:assignguestorders:assignexistingguestorders')
            ->setDescription('Assign existing guest orders to their customer.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->appState->setAreaCode(\Magento\Framework\App\Area::AREA_GLOBAL);

        $output->writeln("<comment>Assign existing guest orders...</comment>");

        try {
            $nbAssigned = $this->order->assignExistingGuestOrders();
        } catch (\Exception $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");
            return Cli::RETURN_FAILURE;
        }

        $output->writeln('<info>' . $nbAssigned . ' guest orders assigned.</info>');

        return Cli::RETURN_SUCCESS;
    }
}
