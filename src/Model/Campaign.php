<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Result;
use WorldNewsGroup\Marketo\Client;

class Campaign extends Model {
    public static $fields = [
        'active',
        'createdAt',
        'description',
        'id',
        'name',
        'programId',
        'programName',
        'type',
        'updatedAt',
        'workspaceName'
    ];

    /**
     * @internal
     * 
     * Assembles objects based on the Result object
     * 
     * @return Campaign[]
     */
    public static function manufacture(Result $result) {
        $objects = [];

        foreach($result->getResults() as $r) {
            $objects[] = new Campaign($r);
        }
        
        return $objects;
    }

    /**
     * Returns a list of campaign records. 
     * Required Permissions: Read-Only Campaigns, Read-Write Campaigns
     * 
     * @param   int[]       $id
     * @param   string[]    $name
     * @param   string[]    $program_name
     * @param   string[]    $workspace_name
     * @param   int         $batch_size
     * @param   string      $next_page_token
     * @param   boolean     $is_triggerable
     * 
     * @return Campaign[] | null
     */
    public static function getCampaigns($id = [], $name = [], $program_name = [], $workspace_name = [], $batch_size = 300, $next_page_token = "", $is_triggerable = false) {
        $query = [
            'batchSize'=>$batch_size,
            'isTriggerable'=>$is_triggerable
        ];

        if( !empty($id) ) $query['id'] = $id;
        if( !empty($name) ) $query['id'] = $name;
        if( !empty($program_name) ) $query['id'] = $program_name;
        if( !empty($workspace_name) ) $query['id'] = $workspace_name;
        if( !empty($next_page_token) ) $query['id'] = $next_page_token;

        return Campaign::manufacture(Client::send('GET', 'campaigns.json', ['query'=>$query]));
    }

    /**
     * Returns the record of a campaign by its id. 
     * Required Permissions: Read-Only Campaigns, Read-Write Campaigns
     * 
     * @deprecated 
     * @return Campaign[] | null
     */
    public static function getCampaignById($campaign_id) {
        return Campaign::manufacture(Client::send('GET', 'campaigns/' . $campaign_id . '.json'));
    }

    /**
     * Remotely schedules a batch campaign to run at a given time. My tokens local to the campaign's parent program can be 
     * overridden for the run to customize content. When using the "cloneToProgramName" parameter described below, this 
     * endpoint is limited to 20 calls per day. 
     * Required Permissions: Execute Campaign
     * 
     * @param   int     $campaign_id
     * @param   array   $schedule_campaign_data
     * 
     * @return Campaign[] | null
     */
    public static function scheduleCampaign($campaign_id, $schedule_campaign_data) {
        return Campaign::manufacture(Client::send('POST', 'campaigns/' . $campaign_id . '/schedule.json', 
            ['body'=>['input'=>$schedule_campaign_data]]));
    }

    /**
     * Passes a set of leads to a trigger campaign to run through the campaign's flow. The designated campaign must have 
     * a Campaign is Requested: Web Service API trigger, and must be active. My tokens local to the campaign's parent 
     * program can be overridden for the run to customize content. A maximum of 100 leads are allowed per call. 
     * Required Permissions: Execute Campaign
     * 
     * @param   int     $campaign_id
     * @param   array   $trigger_campaign_data
     * 
     * @return Campaign[] | null
     */
    public static function requestCampaign($campaign_id, $trigger_campaign_data) {
        return Campaign::manufacture(Client::send('POST', 'campaigns/'. $campaign_id . '/trigger.json', 
            ['body'=>['input'=>$trigger_campaign_data]]));
    }
}