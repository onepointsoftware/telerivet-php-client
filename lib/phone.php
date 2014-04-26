<?php

/**
    Telerivet_Phone
    
    Represents a phone or gateway that you use to send/receive messages via Telerivet.
    
    Fields:
    
      - id (string, max 34 characters)
          * ID of the phone
          * Read-only
      
      - name
          * Name of the phone
          * Updatable via API
      
      - phone_number (string)
          * Phone number of the phone
          * Updatable via API
      
      - phone_type
          * Type of this phone/gateway (e.g. android, twilio, nexmo, etc)
          * Read-only
      
      - time_created (UNIX timestamp)
          * Time the phone was created in Telerivet
          * Read-only
      
      - last_active_time (UNIX timestamp)
          * Approximate time this phone last connected to Telerivet
          * Read-only
      
      - vars (associative array)
          * Custom variables stored for this phone
          * Updatable via API
      
      - project_id
          * ID of the project this phone belongs to
          * Read-only
      
      - battery (int)
          * Current battery level, on a scale from 0 to 100, as of the last time the phone connected
              to Telerivet (only present for Android phones)
          * Read-only
      
      - charging (bool)
          * True if the phone is currently charging, false if it is running on battery, as of the
              last time it connected to Telerivet (only present for Android phones)
          * Read-only
      
      - app_version
          * Currently installed version of Telerivet Android app (only present for Android phones)
          * Read-only
      
      - android_sdk (int)
          * Android SDK level, indicating the approximate version of the Android OS installed on
              this phone; see
              <http://developer.android.com/guide/topics/manifest/uses-sdk-element.html#ApiLevels> (only
              present for Android phones)
          * Read-only
      
      - mccmnc
          * Code indicating the Android phone's current country (MCC) and mobile network operator
              (MNC); see <http://en.wikipedia.org/wiki/Mobile_country_code> (only present for Android
              phones). Note this is a string containing numeric digits, not an integer.
          * Read-only
      
      - manufacturer
          * Android phone manufacturer (only present for Android phones)
          * Read-only
      
      - model
          * Android phone model (only present for Android phones)
          * Read-only
      
      - send_limit (int)
          * Maximum number of SMS messages per hour that can be sent by this Android phone. To
              increase this limit, install additional SMS expansion packs in the Telerivet app. (only
              present for Android phones)
          * Read-only
      
    
    Example Usage:
    -------------
    
    $PROJECT_ID = 'YOUR_PROJECT_ID'; // from https://telerivet.com/dashboard/api
    $PHONE_ID = 'YOUR_PHONE_ID'; 
    
    $project = $telerivet->getProjectById($PROJECT_ID); 
    $phone = $project->getPhoneById($PHONE_ID); 
    
    echo $phone->queryMessages(array(
        'status' => 'queued'
    ))->count();   
    
 */
class Telerivet_Phone extends Telerivet_Entity
{
    /**
        $phone->queryMessages($options)
        
        Queries messages sent or received by this phone.
        
        Arguments:
          - $options (associative array)
            
            - direction
                * Filter messages by direction
                * Allowed values: incoming, outgoing
            
            - message_type
                * Filter messages by message_type
                * Allowed values: sms, mms, ussd, call
            
            - source
                * Filter messages by source
                * Allowed values: phone, provider, web, api, service, webhook, scheduled
            
            - starred (bool)
                * Filter messages by starred/unstarred
            
            - status
                * Filter messages by status
                * Allowed values: ignored, processing, received, sent, queued, failed,
                    failed_queued, cancelled, delivered, not_delivered
            
            - time_created[min] (UNIX timestamp)
                * Filter messages created on or after a particular time
            
            - time_created[max] (UNIX timestamp)
                * Filter messages created before a particular time
            
            - contact_id
                * ID of the contact who sent/received the message
            
            - phone_id
                * ID of the phone that sent/received the message
            
            - sort
                * Sort the results based on a field
                * Allowed values: default
                * Default: default
            
            - sort_dir
                * Sort the results in ascending or descending order
                * Allowed values: asc, desc
                * Default: asc
            
            - page_size (int)
                * Number of results returned per page (max 200)
                * Default: 50
            
            - offset (int)
                * Number of items to skip from beginning of result set
                * Default: 0
          
        Returns:
            Telerivet_APICursor (of Telerivet_Message)
    */
    function queryMessages($options = null)
    {
        return $this->_api->newApiCursor('Telerivet_Message', "{$this->getBaseApiPath()}/messages", $options);
    }

    /**
        $phone->save()
        
        Saves any fields or custom variables that have changed for this phone.
        
    */
    function save()
    {
        parent::save();
    }

    function getBaseApiPath()
    {
        return "/projects/{$this->project_id}/phones/{$this->id}";
    }
}
