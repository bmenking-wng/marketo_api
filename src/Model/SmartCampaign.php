<?php

namespace WorldNewsGroup\Marketo\Model;

use WorldNewsGroup\Marketo\Result;
use WorldNewsGroup\Marketo\Client;

class SmartCampaign extends Model {
    public static $fields = [
        'id',
        'name',
        'description',
        'type',
        'isSystem',
        'isActive',
        'isRequestable',
        'recurrence',
        'qualificationRuleType',
        'qualificationRuleInterval',
        'qualificationRuleUnit',
        'maxMembers',
        'isCommunicationLimitEnabled',
        'smartListId',
        'flowId',
        'parentProgramId',
        'folder',
        'createdAt',
        'updatedAt',
        'workspace',
        'computedUrl',
        'status',
        'url',
        'rules'
    ];

    /**
     * @internal
     * 
     * Assembles Campaign objects based on the Result object
     * 
     * @return SmartCampaign[]
     */
    public static function manufacture(Result $result) {
        $objects = [];

        foreach($result->getResults() as $r) {
            $objects[] = new SmartCampaign($r);
        }
        
        return $objects;
    }  

    /**
     * Returns the smart campaign for the given id. 
     * Required Permissions: Read-Only Assets, Read-Write Assets
     * 
     * @param   int     $smartcampaign_id       Id for the smart campaign.
     * 
     * @return SmartList[] | null     * 
     */
    public static function getSmartCampaignById(int $smartcampaign_id) {
        return SmartList::manufacture(Client::send('GET', 'rest/asset/v1/smartCampaign/' . $smartcampaign_id . '.json'));
    }

    /**
     * Update the smart campaign for the given id. 
     * Required Permissions: Read-Write Assets
     * 
     * @param   int     $smartcampaign_id       Id for the smart campaign.
     * @param   string  $name                   Name of the smart campaign.
     * @param   string  $description            Description of the smart campaign.
     * 
     * @return SmartList[] | null     * 
     */
    public static function updateSmartCampaign(int $smartcampaign_id, string $name = null, string $description = null) {
        $body = [];

        if( !is_null($name) ) $body['name'] = $name;
        if( !is_null($description) ) $body['description'] = $description;

        return SmartList::manufacture(Client::send('POST', 'rest/asset/v1/smartCampaign/' . $smartcampaign_id . '.json', ['body'=>$body]));
    }

    /**
     * Returns the smart campaign for the given name. 
     * Required Permissions: Read-Only Assets, Read-Write Assets
     * 
     * @param   string     $smartcampaign_name       Name for the smart campaign.
     * 
     * @return SmartList[] | null     * 
     */
    public static function getSmartCampaignByName(string $smartcampaign_name) {
        $query = ['name'=>$smartcampaign_name];

        return SmartList::manufacture(Client::send('GET', 'rest/asset/v1/smartCampaign/byName.json', ['query'=>$query]));
    }

    /**
     * Retrieves a Smart List record by its Smart Campaign id. 
     * Required Permissions: Read-Asset or Read-Write Asset
     * 
     * @param   string     $smartcampaign_id       Id for the smart campaign containing smart list to retrieve.
     * @param   boolean    $include_rules          Set true to populate smart list rules. Default false.
     * 
     * @return SmartList[] | null     * 
     */
    public static function getSmartListBySmartCampaignId(string $smartcampaign_id, $include_rules = false) {
        $query= ['includeRules'=>$include_rules];

        return SmartList::manufacture(Client::send('GET', 'rest/asset/v1/smartCampaign/' . $smartcampaign_id . '/smartList.json', ['query'=>$query]));
    }    

    /**
     * Retrieves all smart campaigns. 
     * Required Permissions: Read-Only Assets, Read-Write Assets
     * 
     * @param   int     $max_return             Maximum number of smart campaigns to return. Max 200, default 20
     * @param   int     $offset                 Integer offset for paging
     * @param   string  $folder                 JSON representation of parent folder, with members 'id', and 'type' which may be 'Folder' or 'Program'
     * @param   string  $earliest_updated_at    Exclude smart campaigns prior to this date. Must be valid ISO-8601 string. See Datetime field type description.
     * @param   string  $latest_updated_at      Exclude smart campaigns after this date. Must be valid ISO-8601 string. See Datetime field type description.
     * @param   boolean $is_active              Set true to return only active campaigns. Default false
     * 
     * @return SmartList[] | null     * 
     */
    public static function getSmartCampaigns(int $max_return = 20, int $offset = 0, string $folder = null, string $earliest_updated_at = null, string $latest_updated_at = null, $is_active = false) {
        return SmartList::manufacture(Client::send('GET', 'rest/asset/v1/smartCampaigns.json', ['query'=>$query]));
    }
}