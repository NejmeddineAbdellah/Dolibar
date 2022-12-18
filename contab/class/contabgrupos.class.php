<?php
/* Copyright (C) 2007-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) ---Put here your own copyright and developer email---
 * 					JPFarber - jpfarber@auribox.com, jfarber55@hotmail.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 * 
 * module créé par 106, 117, 97, 110, b, 112, 97, 98, 108, 11, b, 102, 97, 114, 98, 101, 114
 */

/**
 *  \file       dev/skeletons/contabgrupos.class.php
 *  \ingroup    mymodule othermodule1 othermodule2
 *  \brief      This file is an example for a CRUD class file (Create/Read/Update/Delete)
 *				Initialy built by build_class_from_table on 2015-03-19 17:40
 */

// Put here all includes required by your class file
require_once(DOL_DOCUMENT_ROOT."/core/class/commonobject.class.php");
//require_once(DOL_DOCUMENT_ROOT."/societe/class/societe.class.php");
//require_once(DOL_DOCUMENT_ROOT."/product/class/product.class.php");

/**
 *	Put here description of your class
 */
class Contabgrupos extends CommonObject
{
	var $db;							//!< To store db handler
	var $error;							//!< To return error code (or message)
	var $errors=array();				//!< To return several error codes (or messages)
	var $element='contabgrupos';			//!< Id that identify managed objects
	var $table_element='contabgrupos';		//!< Name of table without prefix where object is stored

    var $id;
    
    var $entity;
	var $grupo;
	var $fk_codagr_rel;
	var $fk_codagr_ini;
	var $fk_codagr_fin;
	
	var $tipo_edo_financiero;

    /**
     *  Constructor
     *
     *  @param	DoliDb		$db      Database handler
     */
    function __construct($db)
    {
        $this->db = $db;
        return 1;
    }

    /**
     *  Create object into database
     *
     *  @param	User	$user        User that creates
     *  @param  int		$notrigger   0=launch triggers after, 1=disable triggers
     *  @return int      		   	 <0 if KO, Id of created object if OK
     */
    function create($user, $notrigger=0)
    {
    	global $conf, $langs;
		$error=0;

		// Clean parameters
		//if (isset($this->entity)) $this->entity=trim($this->entity);
		if (isset($this->entity)) {
			$this->entity=trim($this->entity);
		} else {
			$this->entity=$conf->entity;
		}
		if (isset($this->grupo)) $this->grupo=trim($this->grupo);
		if (isset($this->fk_codagr_rel)) $this->fk_codagr_rel=trim($this->fk_codagr_rel);
		if (isset($this->fk_codagr_ini)) $this->fk_codagr_ini=trim($this->fk_codagr_ini);
		if (isset($this->fk_codagr_fin)) $this->fk_codagr_fin=trim($this->fk_codagr_fin);
		if (isset($this->tipo_edo_financiero)) $this->tipo_edo_financiero=trim($this->tipo_edo_financiero);

		// Check parameters
		// Put here code to add control on parameters values

        // Insert request
		$sql = "INSERT INTO ".MAIN_DB_PREFIX."contab_grupos(";
		
		$sql.= "entity,";
		$sql.= "grupo,";
		$sql.= "fk_codagr_rel,";
		$sql.= "fk_codagr_ini,";
		$sql.= "fk_codagr_fin,";
		$sql.= "tipo_edo_financiero";
				
        $sql.= ") VALUES (";
        
        $sql.= " ".(! isset($this->entity)?'NULL':"'".$this->db->escape($this->entity)."'").",";
		$sql.= " ".(! isset($this->grupo)?'NULL':"'".$this->db->escape($this->grupo)."'").",";
		$sql.= " ".(! isset($this->fk_codagr_rel)?'NULL':"'".$this->fk_codagr_rel."'").",";
		$sql.= " ".(! isset($this->fk_codagr_ini)?'NULL':"'".$this->fk_codagr_ini."'").",";
		$sql.= " ".(! isset($this->fk_codagr_fin)?'NULL':"'".$this->fk_codagr_fin."'").",";
		$sql.= " ".(! isset($this->tipo_edo_financiero)?'NULL':"'".$this->tipo_edo_financiero."'")."";

		$sql.= ")";

		$this->db->begin();

	   	dol_syslog(get_class($this)."::create sql=".$sql, LOG_DEBUG);
        $resql=$this->db->query($sql);
    	if (! $resql) { $error++; $this->errors[]="Error ".$this->db->lasterror(); }

		if (! $error)
        {
            $this->id = $this->db->last_insert_id(MAIN_DB_PREFIX."contab_grupos");

			if (! $notrigger)
			{
	            // Uncomment this and change MYOBJECT to your own tag if you
	            // want this action calls a trigger.

	            //// Call triggers
	            //include_once DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
	            //$interface=new Interfaces($this->db);
	            //$result=$interface->run_triggers('MYOBJECT_CREATE',$this,$user,$langs,$conf);
	            //if ($result < 0) { $error++; $this->errors=$interface->errors; }
	            //// End call triggers
			}
        }

        // Commit or rollback
        if ($error)
		{
			foreach($this->errors as $errmsg)
			{
	            dol_syslog(get_class($this)."::create ".$errmsg, LOG_ERR);
	            $this->error.=($this->error?', '.$errmsg:$errmsg);
			}
			$this->db->rollback();
			return -1*$error;
		}
		else
		{
			$this->db->commit();
            return $this->id;
		}
    }
    
    function create_from_firstone() {
    	global $conf;
    	$sql = "CREATE TABLE ".MAIN_DB_PREFIX."contab_grupos_tmp LIKE ".MAIN_DB_PREFIX."contab_grupos";
    	dol_syslog("create_from_firstone - paso 1 de 5 :: sql=".$sql);
    	$this->db->query($sql);
    	
    	$sql = "INSERT INTO ".MAIN_DB_PREFIX."contab_grupos_tmp SELECT * FROM ".MAIN_DB_PREFIX."contab_grupos WHERE entity = 1";
    	dol_syslog("create_from_firstone - paso 2 de 5 :: sql=".$sql);
    	$this->db->query($sql);
    	
    	$sql = "UPDATE ".MAIN_DB_PREFIX."contab_grupos_tmp SET entity = ".$conf->entity." WHERE entity = 1";
    	dol_syslog("create_from_firstone - paso 3 de 5 :: sql=".$sql);
    	$this->db->query($sql);
    	
    	$sql = "INSERT INTO ".MAIN_DB_PREFIX."contab_grupos (entity, grupo, fk_codagr_rel, fk_codagr_ini, fk_codagr_fin, tipo_edo_financiero) SELECT entity, grupo, fk_codagr_rel, fk_codagr_ini, fk_codagr_fin, tipo_edo_financiero FROM ".MAIN_DB_PREFIX."contab_grupos_tmp WHERE entity = ".$conf->entity;
    	dol_syslog("create_from_firstone - paso 4 de 5 :: sql=".$sql);
    	$this->db->query($sql);
    	
    	$sql = "DROP TABLE ".MAIN_DB_PREFIX."contab_grupos_tmp";
    	dol_syslog("create_from_firstone - paso 5 de 5 :: sql=".$sql);
    	$this->db->query($sql);
    }

    /**
     *  Load object in memory from the database
     *
     *  @param	int		$id    Id object
     *  @return int          	<0 if KO, >0 if OK
     */
    function fetch($id, $tipo_edo_financiero)
    {
    	global $langs,$conf;
        $sql = "SELECT";
		$sql.= " t.rowid,";
		
		$sql.= " t.grupo,";
		$sql.= " t.fk_codagr_rel,";
		$sql.= " t.fk_codagr_ini,";
		$sql.= " t.fk_codagr_fin,";
		$sql.= " t.tipo_edo_financiero";
		
        $sql.= " FROM ".MAIN_DB_PREFIX."contab_grupos as t";
        $sql.= " WHERE t.rowid = ".$id;
        $sql.= " AND tipo_edo_financiero =".$tipo_edo_financiero;
        $sql.= " AND entity = ".$conf->entity;

    	dol_syslog(get_class($this)."::fetch sql=".$sql, LOG_DEBUG);
        $resql=$this->db->query($sql);
        if ($resql)
        {
            if ($this->db->num_rows($resql))
            {
                $obj = $this->db->fetch_object($resql);

                $this->id    = $obj->rowid;
                
				$this->grupo = $obj->grupo;
				$this->fk_codagr_rel = $obj->fk_codagr_rel;
				$this->fk_codagr_ini = $obj->fk_codagr_ini;
				$this->fk_codagr_fin = $obj->fk_codagr_fin;
				$this->tipo_edo_financiero = $obj->tipo_edo_financiero;
                
            }
            $this->db->free($resql);

            return 1;
        }
        else
        {
      	    $this->error="Error ".$this->db->lasterror();
            dol_syslog(get_class($this)."::fetch ".$this->error, LOG_ERR);
            return -1;
        }
    }
    
    function fetch_next($id = 0, $tipo_edo_financiero, $more_tables = 0)
    {
    	global $langs,$conf;
    	$sql = "SELECT";
    	$sql.= " t.rowid,";
    
    	$sql.= " t.grupo,";
    	$sql.= " t.fk_codagr_rel,";
    	$sql.= " t.fk_codagr_ini,";
    	$sql.= " t.fk_codagr_fin,";
    	$sql.= " t.tipo_edo_financiero";
    	
    	if ($more_tables) {
    		$sql .= ", ifnull(s1.codagr,0) codagr_rel, ifnull(s1.descripcion, '') desc_rel, ";
    		$sql .= " ifnull(s2.codagr,0) codagr_ini, ifnull(s2.descripcion, '') desc_ini, ";
    		$sql .= " ifnull(s3.codagr,0) codagr_fin, ifnull(s3.descripcion, '') desc_fin ";
    	}
    	
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_grupos as t";
    	
    	if ($more_tables) {
    		$sql .= " LEFT JOIN ".MAIN_DB_PREFIX."contab_sat_ctas s1 ";
    		$sql .= " ON t.fk_codagr_rel = s1.rowid ";
    		$sql .= " LEFT JOIN ".MAIN_DB_PREFIX."contab_sat_ctas s2 ";
    		$sql .= " ON t.fk_codagr_ini = s2.rowid ";
    		$sql .= " LEFT JOIN ".MAIN_DB_PREFIX."contab_sat_ctas s3 ";
    		$sql .= " ON t.fk_codagr_fin = s3.rowid ";
    	}
    	
    	if ($id == 0) {
    		$sql.= " WHERE tipo_edo_financiero =".$tipo_edo_financiero;
    	} else {
	    	$sql.= " WHERE t.rowid > ".$id;
	    	$sql.= " AND tipo_edo_financiero =".$tipo_edo_financiero;
    	}
    	$sql.= " AND entity = ".$conf->entity;
    	$sql.= " ORDER BY t.rowid LIMIT 1";
   // print $sql."<br>";
    	dol_syslog(get_class($this)."::fetch_next sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id    = $obj->rowid;
    
    			$this->grupo = $obj->grupo;
    			$this->fk_codagr_rel = $obj->fk_codagr_rel;
    			$this->fk_codagr_ini = $obj->fk_codagr_ini;
    			$this->fk_codagr_fin = $obj->fk_codagr_fin;
    			$this->tipo_edo_financiero = $obj->tipo_edo_financiero;
    			
    			if ($more_tables) {
	    			$this->codagr_rel = $obj->codagr_rel;
    				$this->desc_rel = $obj->desc_rel;
    				$this->codagr_ini = $obj->codagr_ini;
    				$this->desc_ini = $obj->desc_ini;
    				$this->codagr_fin = $obj->codagr_fin;
    				$this->desc_fin = $obj->desc_fin;
    			}
    			
    			$this->db->free($resql);
    			
    			return 1;
    			
    		} else {
    			$this->db->free($resql);
    			
    			return 0;
    		}
    	}
    	else
    	{
    		$this->error="Error ".$this->db->lasterror();
    		dol_syslog(get_class($this)."::fetch_next ".$this->error, LOG_ERR);
    		return -1;
    	}
    }
    
    function fetch_next_codagr_ini($id = 0, $more_tables = 0)
    {
    	global $langs,$conf;
    	$sql = "SELECT";
    	$sql.= " t.rowid,";
    
    	$sql.= " t.grupo,";
    	$sql.= " t.fk_codagr_rel,";
    	$sql.= " t.fk_codagr_ini,";
    	$sql.= " t.fk_codagr_fin,";
    	$sql.= " t.tipo_edo_financiero";
    	
    	if ($more_tables) {
    		$sql .= ", ifnull(s1.codagr,0) codagr_rel, ifnull(s1.descripcion, '') desc_rel, ";
    		$sql .= " ifnull(s2.codagr,0) codagr_ini, ifnull(s2.descripcion, '') desc_ini, ";
    		$sql .= " ifnull(s3.codagr,0) codagr_fin, ifnull(s3.descripcion, '') desc_fin ";
    	}
    
    
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_grupos as t";
    	if ($more_tables) {
    		$sql .= " LEFT JOIN ".MAIN_DB_PREFIX."contab_sat_ctas s1 ";
    		$sql .= " ON t.fk_codagr_rel = s1.rowid ";
    		$sql .= " LEFT JOIN ".MAIN_DB_PREFIX."contab_sat_ctas s2 ";
    		$sql .= " ON t.fk_codagr_ini = s2.rowid ";
    		$sql .= " LEFT JOIN ".MAIN_DB_PREFIX."contab_sat_ctas s3 ";
    		$sql .= " ON t.fk_codagr_fin = s3.rowid ";
    	}
    	if ($id == 0) {
    		$sql.= " WHERE 1 ";
    	} else {
    		$sql.= " WHERE t.fk_codagr_ini > ".$id;
    	}
    	$sql.= " AND entity = ".$conf->entity;
    	$sql.= " ORDER BY t.fk_codagr_ini LIMIT 1";
    
    	dol_syslog(get_class($this)."::fetch_next_codagr_ini sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id    = $obj->rowid;
    
    			$this->grupo = $obj->grupo;
    			$this->fk_codagr_rel = $obj->fk_codagr_rel;
    			$this->fk_codagr_ini = $obj->fk_codagr_ini;
    			$this->fk_codagr_fin = $obj->fk_codagr_fin;
    			$this->tipo_edo_financiero = $obj->tipo_edo_financiero;
    			 
    			if ($more_tables) {
    				$this->codagr_rel = $obj->codagr_rel;
    				$this->desc_rel = $obj->desc_rel;
    				$this->codagr_ini = $obj->codagr_ini;
    				$this->desc_ini = $obj->desc_ini;
    				$this->codagr_fin = $obj->codagr_fin;
    				$this->desc_fin = $obj->desc_fin;
    			}
    			 
    			$this->db->free($resql);
    			 
    			return 1;
    		} else {
    			$this->db->free($resql);
    			 
    			return 0;
    		}
    	}
    	else
    	{
    		$this->error="Error ".$this->db->lasterror();
    		dol_syslog(get_class($this)."::fetch_next_codagr_ini ".$this->error, LOG_ERR);
    		return -1;
    	}
    }

    /**
     *  Update object into database
     *
     *  @param	User	$user        User that modifies
     *  @param  int		$notrigger	 0=launch triggers after, 1=disable triggers
     *  @return int     		   	 <0 if KO, >0 if OK
     */
    function update($user=0, $notrigger=0)
    {
    	global $conf, $langs;
		$error=0;

		// Clean parameters
        
		if (isset($this->grupo)) $this->grupo=trim($this->grupo);
		if (isset($this->fk_codagr_rel)) $this->fk_codagr_rel=trim($this->fk_codagr_rel);
		if (isset($this->fk_codagr_ini)) $this->fk_codagr_ini=trim($this->fk_codagr_ini);
		if (isset($this->fk_codagr_fin)) $this->fk_codagr_fin=trim($this->fk_codagr_fin);
		if (isset($this->tipo_edo_financiero)) $this->tipo_edo_financiero=trim($this->tipo_edo_financiero);        

		// Check parameters
		// Put here code to add a control on parameters values

        // Update request
        $sql = "UPDATE ".MAIN_DB_PREFIX."contab_grupos SET";
        
		$sql.= " grupo=".(isset($this->grupo)?"'".$this->db->escape($this->grupo)."'":"null").",";
		$sql.= " fk_codagr_rel=".(isset($this->fk_codagr_rel)?$this->fk_codagr_rel:"null").",";
		$sql.= " fk_codagr_ini=".(isset($this->fk_codagr_ini)?$this->fk_codagr_ini:"null").",";
		$sql.= " fk_codagr_fin=".(isset($this->fk_codagr_fin)?$this->fk_codagr_fin:"null").",";
		$sql.= " tipo_edo_financiero=".(isset($this->tipo_edo_financiero)?$this->tipo_edo_financiero:"null")."";
        
        $sql.= " WHERE rowid=".$this->id;
        $sql.= " AND entity = ".$conf->entity;

		$this->db->begin();

		dol_syslog(get_class($this)."::update sql=".$sql, LOG_DEBUG);
        $resql = $this->db->query($sql);
    	if (! $resql) { $error++; $this->errors[]="Error ".$this->db->lasterror(); }

		if (! $error)
		{
			if (! $notrigger)
			{
	            // Uncomment this and change MYOBJECT to your own tag if you
	            // want this action calls a trigger.

	            //// Call triggers
	            //include_once DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
	            //$interface=new Interfaces($this->db);
	            //$result=$interface->run_triggers('MYOBJECT_MODIFY',$this,$user,$langs,$conf);
	            //if ($result < 0) { $error++; $this->errors=$interface->errors; }
	            //// End call triggers
	    	}
		}

        // Commit or rollback
		if ($error)
		{
			foreach($this->errors as $errmsg)
			{
	            dol_syslog(get_class($this)."::update ".$errmsg, LOG_ERR);
	            $this->error.=($this->error?', '.$errmsg:$errmsg);
			}
			$this->db->rollback();
			return -1*$error;
		}
		else
		{
			$this->db->commit();
			return 1;
		}
    }

 	/**
	 *  Delete object in database
	 *
     *	@param  User	$user        User that deletes
     *  @param  int		$notrigger	 0=launch triggers after, 1=disable triggers
	 *  @return	int					 <0 if KO, >0 if OK
	 */
	function delete($user, $notrigger=0)
	{
		global $conf, $langs;
		$error=0;

		$this->db->begin();

		if (! $error)
		{
			if (! $notrigger)
			{
				// Uncomment this and change MYOBJECT to your own tag if you
		        // want this action calls a trigger.

		        //// Call triggers
		        //include_once DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
		        //$interface=new Interfaces($this->db);
		        //$result=$interface->run_triggers('MYOBJECT_DELETE',$this,$user,$langs,$conf);
		        //if ($result < 0) { $error++; $this->errors=$interface->errors; }
		        //// End call triggers
			}
		}

		if (! $error)
		{
    		$sql = "DELETE FROM ".MAIN_DB_PREFIX."contab_grupos";
    		$sql.= " WHERE rowid=".$this->id;
    		$sql.= " AND entity = ".$conf->entity;

    		dol_syslog(get_class($this)."::delete sql=".$sql);
    		$resql = $this->db->query($sql);
        	if (! $resql) { $error++; $this->errors[]="Error ".$this->db->lasterror(); }
		}

        // Commit or rollback
		if ($error)
		{
			foreach($this->errors as $errmsg)
			{
	            dol_syslog(get_class($this)."::delete ".$errmsg, LOG_ERR);
	            $this->error.=($this->error?', '.$errmsg:$errmsg);
			}
			$this->db->rollback();
			return -1*$error;
		}
		else
		{
			$this->db->commit();
			return 1;
		}
	}

	/**
	 *	Load an object from its id and create a new one in database
	 *
	 *	@param	int		$fromid     Id of object to clone
	 * 	@return	int					New id of clone
	 */
	function createFromClone($fromid)
	{
		global $user,$langs;

		$error=0;

		$object=new Contabgrupos($this->db);

		$this->db->begin();

		// Load source object
		$object->fetch($fromid);
		$object->id=0;
		$object->statut=0;

		// Clear fields
		// ...

		// Create clone
		$result=$object->create($user);

		// Other options
		if ($result < 0)
		{
			$this->error=$object->error;
			$error++;
		}

		if (! $error)
		{
		}

		// End
		if (! $error)
		{
			$this->db->commit();
			return $object->id;
		}
		else
		{
			$this->db->rollback();
			return -1;
		}
	}

	/**
	 *	Initialise object with example values
	 *	Id must be 0 if object instance is a specimen
	 *
	 *	@return	void
	 */
	function initAsSpecimen()
	{
		$this->id=0;
		
		$this->entity='';
		$this->grupo='';
		$this->fk_codagr_rel='';
		$this->fk_codagr_ini='';
		$this->fk_codagr_fin='';
		$this->tipo_edo_financiero='';		
	}

}
