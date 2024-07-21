<?php

/**
 * BillingViewCard - presentation view of a patient's billing information in a card widget.
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Stephen Nielson <snielson@discoverandchange.com>
 * @copyright Copyright (c) 2024 Care Management Solutions, Inc. <stephen.waite@cmsvt.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

namespace OpenEMR\Modules\HealthplusPeriscope;

use OpenEMR\Events\Patient\Summary\Card\CardModel;
use OpenEMR\Events\Patient\Summary\Card\RenderEvent;

class InfectionViewCard extends CardModel
{
    private const TEMPLATE_FILE = '../templates/patient/infection.html.twig';

    private const CARD_ID = 'periscope';

    private $pid;

    public function __construct($pid, array $opts = [])
    {
        $this->pid = $pid;
        $opts = $this->setupOpts($opts);
        parent::__construct($opts);
    }
    
    private function setupOpts(array $opts)
    {
        $opts['acl'] = [];
        $opts['title'] = xl('Billing');
        $opts['identifier'] = self::CARD_ID;
        $opts['templateFile'] = self::TEMPLATE_FILE;
        $opts['initiallyCollapsed'] = 0;
        $opts['templateVariables'] = [];
        return $opts;
    }

    public function getTemplateVariables(): array
    {
        // having us do this allows us to defer the execution of the expensive functions until we need them
        $templateVars = parent::getTemplateVariables();
        return $templateVars;
    }

    private function setupBillingData()
    {
        $pid = $this->pid;
        $ed = $this->getEventDispatcher();
        $forceBillingExpandAlways = ($GLOBALS['force_billing_widget_open']) ? true : false;
        $patientbalance = get_patient_balance($pid, false);
        $insurancebalance = get_patient_balance($pid, true) - $patientbalance;
        $totalbalance = $patientbalance + $insurancebalance;
        $unallocated_amt = get_unallocated_patient_balance($pid);
        $collectionbalance = get_patient_balance($pid, false, false, true);

        $id = self::CARD_ID . "_ps_expand";
        $dispatchResult = $ed->dispatch(new RenderEvent('billing'), RenderEvent::EVENT_HANDLE);

        $viewArgs = [
            'title' => xl('Billing'),
            'id' => $id,
            'initiallyCollapsed' => (getUserSetting($id) == 0) ? true : false,
            'hideBtn' => true,
            'patientBalance' => $patientbalance,
            'insuranceBalance' => $insurancebalance,
            'totalBalance' => $totalbalance,
            'collectionBalance' => $collectionbalance,
            'unallocated' => $unallocated_amt,
            'forceAlwaysOpen' => $forceBillingExpandAlways,
            'prependedInjection' => $dispatchResult->getPrependedInjection(),
            'appendedInjection' => $dispatchResult->getAppendedInjection(),
        ];

        if (!empty($this->billingNote)) {
            $viewArgs['billingNote'] = $this->billingNote;
        }

        if (!empty($this->primaryInsurance['provider'])) {
            $viewArgs['provider'] = true;
            $viewArgs['insName'] = $this->insco_name;
            $viewArgs['copay'] = $this->primaryInsurance['copay'];
            $viewArgs['effDate'] = $this->primaryInsurance['effdate'];
            $viewArgs['effDateEnd'] = $this->primaryInsurance['effdate_end'];
        }
        return $viewArgs;
    }
}
