<?php

/**
    Telerivet_DataTable
    
    Represents a custom data table that can store arbitrary rows.
    
    For example, poll services use data tables to store a row for each response.
    
    It is currently only possible to create new data tables via the web UI; however,
    after a table is created, you can add/update/delete rows via the API.
    
    Fields:
    
      - id (string, max 34 characters)
          * ID of the data table
          * Read-only
      
      - name
          * Name of the data table
          * Updatable via API
      
      - num_rows (int)
          * Number of rows in the table
          * Read-only
      
      - project_id
          * ID of the project this data table belongs to
          * Read-only
      
 */
class Telerivet_DataTable extends Telerivet_Entity
{
    /**
        $table->createRow($options)
        
        Adds a new row to this data table.
        
        Arguments:
          - $options (associative array)
            
            - contact_id
                * ID of the contact that this row is associated with (if applicable)
            
            - from_number (string)
                * Phone number that this row is associated with (if applicable)
            
            - vars (associative array)
                * Custom variables and values to set for this data row
          
        Returns:
            Telerivet_DataRow
     */
    function createRow($options)
    {                                          
        $data = $this->_api->doRequest("POST", "{$this->getBaseApiPath()}/rows", $options);
        return new Telerivet_DataRow($this->_api, $data);
    }
    
    /**
        $table->queryRows($options)
        
        Queries rows in this data table.
        
        Arguments:
          - $options (associative array)
            
            - time_created (UNIX timestamp)
                * Filter data rows by the time they were created
                * Allowed modifiers: time_created[exists], time_created[ne], time_created[min],
                    time_created[max]
            
            - vars (object)
                * Filter data rows by value of a custom variable (e.g. vars[q1], vars[foo], etc.)
                * Allowed modifiers: vars[foo][exists], vars[foo][ne], vars[foo][prefix],
                    vars[foo][not_prefix], vars[foo][gte], vars[foo][gt], vars[foo][lt], vars[foo][lte],
                    vars[foo][min], vars[foo][max]
            
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
          
        Returns:
            Telerivet_APICursor (of Telerivet_DataRow)
     */
    function queryRows($options = null)
    {
        return $this->_api->newApiCursor('Telerivet_DataRow', "{$this->getBaseApiPath()}/rows", $options);
    }

    /**
        $table->getRowById($id)
        
        Gets a row in this table by its ID.
        
        Arguments:
          - $id (ID of the row)
              * Required
          
        Returns:
            Telerivet_DataRow
     */
    function getRowById($id)
    {                                          
        return new Telerivet_DataRow($this->_api, array('id' => $id, 'table_id' => $this->id, 'project_id' => $this->project_id), false);
    }
    
    /**
        $table->save()
        
        Saves any fields that have changed for this data table.
        
     */
    function save()
    {        
        parent::save();
    }
        
    function getBaseApiPath()
    {
        return "/projects/{$this->project_id}/tables/{$this->id}";
    }
}
