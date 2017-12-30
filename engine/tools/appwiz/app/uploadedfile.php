<?php

/**
 * ZnetDK, Starter Web Application for rapid & easy development
 * See official website http://www.znetdk.fr 
 * Copyright (C) 2015 Pascal MARTINEZ (contact@znetdk.fr)
 * License GNU GPL http://www.gnu.org/licenses/gpl-3.0.html GNU GPL
 * --------------------------------------------------------------------
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * --------------------------------------------------------------------
 * App Wizard File upload manager
 *
 * File version: 1.0
 * Last update: 09/18/2015
 */

namespace app;

/**
 * App Wizard File upload manager
 */
class UploadedFile {
    private $fileHttpName = NULL;
    private $maxFileSize = 0;
    private $targetApplicationID;
    private $targetFileName;
    private $fullFileName;
    private $sourceFileName;    
    
    /**
     * Instantiates the class' object
     * @param type $fileHttpName Name of the POST parameter in which the file
     * has been uploaded
     * @param type $targetFileName Target file name of the uploaded file
     * @param type $targetApplicationID Identifier of the application where the
     * target file is to move 
     * @param type $maxFileSize Maximum size of the uploaded file
     */
    public function __construct($fileHttpName, $targetFileName, $targetApplicationID,
            $maxFileSize) {
        $this->fileHttpName = $fileHttpName;
        $this->targetFileName = $targetFileName;
        $this->targetApplicationID = $targetApplicationID;
        $this->maxFileSize = $maxFileSize;
    }
    
    /**
     * Upload the file specified when the object has been instantiated
     * @throws \Exception Thrown when a error is detected getting the file
     * informations and when it is moved.
     */
    public function upload() {
        $request = new \Request();
        try {
            $fileInfos = $request->getUploadedFileInfos($this->fileHttpName);
            $this->sourceFileName = $fileInfos['basename'];
        } catch(\Exception $ex) {
            \General::writeErrorLog('APPWIZ', $ex->getMessage());
            throw $ex;
        }
        $targetFileName = $this->targetFileName . '.' . strtolower($fileInfos['extension']);
        $this->fullFileName = ZNETDK_ROOT
            . \General::getApplicationRelativePath($this->targetApplicationID)
            . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'images'
            . DIRECTORY_SEPARATOR . $targetFileName;
        if (file_exists($this->fullFileName)
                && !unlink($this->fullFileName)) { // Remove existing file...
            $errmsg = "Unable to remove the file ${targetFullFileName}!";
            \General::writeErrorLog('APPWIZ', $errmsg);
            throw new \Exception($errmsg);
        }
        try {
            $request->moveImageFile($this->fileHttpName, $this->fullFileName,
                    $this->maxFileSize);
        } catch (\Exception $ex) {
            $errmsg = $ex . ' - ' . $this->fullFileName . ' - ' . $this->sourceFileName;
            \General::writeErrorLog('APPWIZ', $errmsg);
            throw $ex;
        }
    }
    
    public function getFullFileName() {
        return $this->fullFileName;
    }
    
    public function getSourceFileName() {
        return $this->sourceFileName;
    }
    
    public function getMaxFileSize() {
        return $this->maxFileSize;
    }
}
