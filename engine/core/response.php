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
 * Core API for the definition of the HTTP response data
 *
 * File version: 1.6
 * Last update: 05/06/2017 
 */

/**
 * HTTP response returned to the web browser (view content or data in JSON format)
 */
class Response {

    private $responseData;
    private $viewName;
    private $viewType; /* 'view' or 'help' */
    private $viewCaller;
    private $fileToDownload;
    private $isFileDisplayedInline;
    private $filenameToDownload;
    private $printingObject;
    private $printingFileName;
    private $dataToDownloadAsCsv;

    /**
     * When a \Response object is instanciated, the authentication is checked 
     * for the current user.
     * @param boolean $checkAuthentication Indicates whether the authentication
     * of the current user must be checked. By default, authentication is checked.
     */
    public function __construct($checkAuthentication = TRUE) {
        if ($checkAuthentication) {
            // HTTP error 401 sent if user is not authenticated 
            \UserSession::isAuthenticated();
        }
    }

    /**
     * Adds a value to the HTTP response returned in a JSON format 
     * @param string $name Name of the variable in which the value is to be
     * returned in the response
     * @param string $value Value to add to the response
     */
    public function __set($name, $value) {
        // Generic response data setter
        $this->responseData[$name] = $value;
    }

    public function __get($name) {
        if (key_exists($name, $this->responseData)) {
            return $this->responseData[$name];
        } else {
            $message = "RSP-005: the '$name' property does not exist!";
            \General::writeErrorLog('ZNETDK ERROR', $message, TRUE);
            throw new \ZDKException($message);
        }
    }
    /**
     * Specifies the view to render as response for the HTTP request
     * @param string $viewName Name of the view to render
     * @param string $viewType Type of view to render ('view' or 'help').
     * @param string $viewCaller Caller of the setViewMethod
     */
    public function setView($viewName, $viewType, $viewCaller = NULL) {
        $this->viewName = $viewName;
        $this->viewType = $viewType;
        $this->viewCaller = is_null($viewCaller)
            ? debug_backtrace()[1]['class']
            : $viewCaller;
    }

    /**
     * Set the HTTP response values from an array 
     * @param array $response Values to set for the response
     */
    public function setResponse($response) {
        $this->responseData = $response;
    }

    /**
     * Defines in the HTTP Response the message that confirms that the action has
     * been executed successfully.
     * @param string $summary Title which summarizes the message
     * @param string $message Detailed message 
     */
    public function setSuccessMessage($summary, $message) {
        $this->msg = $message;
        $this->summary = $summary;
        $this->success = TRUE;
    }
    
    /**
     * Defines in the HTTP Response the message that confirms that the action has
     * been executed successfully as a warning.
     * @param string $summary Title which summarizes the message
     * @param string $message Detailed message 
     */
    public function setWarningMessage($summary, $message) {
        $this->setSuccessMessage($summary, $message);
        $this->warning = TRUE;
    }
    
    /**
     * Defines in the HTTP Response the message that notifies that the action has
     * been executed with functional errors.
     * @param string $summary Title which summarizes the message
     * @param string $message Detailed message 
     */
    public function setFailedMessage($summary, $message, $field = null) {
        $this->msg = $message;
        $this->summary = $summary;
        if (!is_null($field)) {
            $this->ename = $field;
        }
        $this->success = FALSE;
    }
    
    /**
     * Returns a HTTP Response 500 with a custom message that alerts that the
     * action has encountered a severe error.
     * @param string $summary Title which summarizes the message
     * @param string $message Detailed message 
     */
    public function setCriticalMessage($message, $exception,$isCore=FALSE) {
        $logMessage = $message . ": code='" . $exception->getCode() . "', message='"
                . $exception->getMessage();
        \General::writeErrorLog('ZNETDK ERROR', $logMessage, $isCore);
        $this->doHttpError(500, LC_MSG_CRI_ERR_SUMMARY, \General::getFilledMessage(LC_MSG_CRI_ERR_DETAIL, $message));
    }
    
    /**
     * Specifies the name of the file to return as HTTP response 
     * @param string $filepath Full path of the file to download
     * @param boolean $forDisplayInline If set to TRUE, the file is downloaded
     * with the 'content-disposition' attribute set to 'inline' in the HTTP 
     * response header.
     * @param string $downloadedFilename The name of the file once downloaded.
     * If not set, the name specified for the $filepath parameter is used instead. 
     */
    public function setFileToDownload($filepath, $forDisplayInline = FALSE, $downloadedFilename = NULL) {
        if (\Request::getMethod() === 'POST') {
            $message = "RSP-001: the 'setFileToDownload()' method can't be called "
                    . "in response of a POST HTTP request method!";
            \General::writeErrorLog('ZNETDK ERROR', $message, TRUE);
            throw new \ZDKException($message);
        }
        if (file_exists($filepath)) {
            $this->fileToDownload = $filepath;
            $this->isFileDisplayedInline = $forDisplayInline;
        } else {
            $message = 'RSP-002: the requested file does not exist!';
            \General::writeErrorLog('ZNETDK ERROR', $message, TRUE);
            throw new \ZDKException($message);
        }
        $this->filenameToDownload = $downloadedFilename;
    }
    
    /**
     * Set the printing to output
     * @param FPDF $printingObject FPDF object to output
     * @param string $fileName File name of the PDF document
     */
    public function setPrinting($printingObject, $fileName = NULL) {
        if (\Request::getMethod() === 'POST') {
            $message = "RSP-003: the 'setPrinting()' method can't be called "
                    . "in response of a POST HTTP request method!";
            \General::writeErrorLog('ZNETDK ERROR', $message, TRUE);
            throw new \ZDKException($message);
        }
        if (method_exists($printingObject,'Output')) {
            $this->printingObject = $printingObject;
            $this->printingFileName = $fileName;
        } else {
            $message = "RSP-004: the specified FPDF printing object must have a method named 'Output'!";
            \General::writeErrorLog('ZNETDK ERROR', $message, TRUE);
            throw new \ZDKException($message);
        }
    }

    /**
     * Sets the CSV data in response of the download request
     * @param array $rowData
     * @param string $fileName
     * @param array $header
     * @param boolean $forDisplayInline
     */
    public function setDataForCsv($rowData, $fileName, $header = NULL, $forDisplayInline = FALSE) {
        $data = $rowData;
        if (!is_null($header)) {
            array_unshift($data, $header);     
        }
        $this->dataToDownloadAsCsv = $data;
        $this->fileToDownload = $fileName;
        $this->isFileDisplayedInline = $forDisplayInline;
    }
    
    /**
     * Returns an HTTP Response 401, 404 or 500.
     * @param int $http_error HTTP error value
     * @param string $summary Summary of the error
     * @param string $message Message of the error
     * @param boolean $doExit Specifies whether the PHP script execution must be
     * stopped (TRUE by default) or not (FALSE)
     */
    public function doHttpError($http_error,$summary,$message,$doExit=TRUE) {
        if (\Request::getMethod() === 'POST') {
            switch ($http_error) {
                case 404: $header = "HTTP/1.0 404 Not Found";
                    break;
                case 401: $header = "HTTP/1.0 401 Unauthorized";
                    break;
                default: $header = "HTTP/1.0 500 Internal Server Error";
            }
            if (!headers_sent()) {
                header($header);
            }
            $this->summary = $summary;
            $this->msg = $message;
            $this->output();
        } else { // No HTTP Error when view is rendered with the page layout
            if (!CFG_VIEW_PAGE_RELOAD) {
                echo '<html><head><meta charset="UTF-8">';
                echo '<title>'.$summary. ' | '. LC_PAGE_TITLE . '</title></head><body>';
            }
            echo '<span class="pui-growl-image zdk-image-fatal"></span><h3>'.
                $summary.'</h3><p>'.$message.'</p>';
            if (!CFG_VIEW_PAGE_RELOAD) {
                echo '</body></html>';
            }
        }
        if ($doExit) { exit; }
    }

    /**
     * Outputs the response by :
     *   - returning the PHP view content if the setView() method has been 
     *     called before,
     *   - or by returning an array in JSON format from the data set for the
     *     response if the methods setResponse() or set[variableName]() have 
     *     been called before.    
     * @return boolean 
     */
    public function output() {
        if (isset($this->viewName)) {
            $allPhpViewFile = $this->getViewSearchPaths();
            \ErrorHandler::suspend();
            $arrayIndex = 0;
            foreach ($allPhpViewFile as $phpViewFile) {
                if (include $phpViewFile) {
                    $position = $arrayIndex % 2 ? -4 : -7;
                    // Include view UI events script if exists...
                    $ui_events_script = substr($phpViewFile, 0, $position) . '-ui-events.php';
                    include $ui_events_script;
                    // Include view UI init script if exists...
                    $ui_init_script = substr($phpViewFile, 0, $position) . '-ui-init.php';                    
                    include $ui_init_script;
                    // Finally, indicate View exists...
                    \ErrorHandler::restart();
                    return TRUE;
                }
                $arrayIndex += 1;
            }
            \ErrorHandler::restart();
            // No view script found!
            if ($this->viewType === 'help') {
                // For help pages, no error 404 is returned, just a "Not found help" status
                $this->success = FALSE;
                $this->msg = LC_MSG_WARN_HELP_NOTFOUND;
                $this->json_output();
                return TRUE;
            } else {
                return FALSE;
            }
            
        } elseif (isset($this->dataToDownloadAsCsv)) {
            $this->csv_output();
        } elseif (isset($this->fileToDownload)) { // File download
            $this->download();
        } elseif (isset($this->printingObject)) { // PDF printing
            if (is_null($this->printingFileName)) {
                $this->printingObject->Output();
            } else {
                $this->printingObject->Output($this->printingFileName,'I');
            }
        } else { // Just response data in JSON format is sent
            $this->json_output();
        }
    }
    
    /**
     * Returns the search paths of the specified view for the response object
     * @return array All the search paths matching the view set as a response
     */
    private function getViewSearchPaths() {
        $enginePath = $this->viewType . '/' . $this->viewName;
        $applicationPath = 'app/' . $enginePath;
        $languageSuffix = '_' . \UserSession::getLanguage() . '.php';
        // First, search the view in the application
        $allPhpViewFile = array($applicationPath . $languageSuffix,
            $applicationPath . '.php');
        // Next, search the view in the modules
        $localizedViewModule = \General::getModules('mod' . DIRECTORY_SEPARATOR . $this->viewType
                . DIRECTORY_SEPARATOR . $this->viewName . $languageSuffix);
        $viewModule = \General::getModules('mod' . DIRECTORY_SEPARATOR . $this->viewType
                . DIRECTORY_SEPARATOR . $this->viewName . '.php');
        if ($localizedViewModule || $viewModule) {
            $allPhpViewFile[] = $localizedViewModule . '/mod/' . $enginePath . $languageSuffix;
            $allPhpViewFile[] = $viewModule . '/mod/' . $enginePath . '.php';
        }
        // Finally search the view in the ZnetDK engine
        $allPhpViewFile[] = $enginePath . $languageSuffix;
        $allPhpViewFile[] = $enginePath . '.php';
        return $allPhpViewFile;
    }

    /**
     * Render the HTTP response in JSON format
     */
    private function json_output() {
        if (!isset($this->responseData)) {
            $action = \Request::getController() . '::' . \Request::getAction();
            $this->doHttpError(500,'Empty response...', 
                "No JSON response has been returned by the " . "'$action' action!");
        }
        $encodingOption = defined('JSON_PARTIAL_OUTPUT_ON_ERROR') ? JSON_PARTIAL_OUTPUT_ON_ERROR : 0;
        $jsonOutput = json_encode($this->responseData, $encodingOption);
        if ($jsonOutput === FALSE) {
            $action = \Request::getController() . '::' . \Request::getAction();
            $jsonError = json_last_error();
            if (function_exists('json_last_error_msg')) {
                $jsonErrorMsg = ': ' . json_last_error_msg();
            } else {
                $jsonErrorMsg = NULL;
            }
            $this->setResponse(NULL);
            $this->doHttpError(500,'JSON encoding...', 
                "Unable to encode in JSON format the response returned by the "
                . "'$action' action (error $jsonError" . $jsonErrorMsg . ")");
        } else {
            header('Content-type: application/json');
            echo $jsonOutput;
        }
    }
    
    /**
     * Returns the content type for the file to download
     * @return string File content type
     */
    private function getContentType() {
        $knownExtensions = array(
            'jpg'=>'image/jpeg',
            'jpeg'=>'image/jpeg',
            'png'=>'image/png',
            'bmp'=>'image/bmp',
            'gif'=>'image/gif',
            'pdf'=>'application/pdf',
            'txt'=>'text/plain',
            'csv'=>'application/vnd.ms-excel',
            'doc'=>'application/msword',
            'docx'=>'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls'=>'application/vnd.ms-excel',
            'xlsx'=>'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $fileExtension = pathinfo($this->fileToDownload, PATHINFO_EXTENSION);
        return array_key_exists($fileExtension, $knownExtensions)
                ? $knownExtensions[$fileExtension] : 'application/' . $fileExtension;
    }
    
    /**
     * Outputs the file header of the downloaded file
     * @param boolean $fileSizeSpecified Specifies whether the file size is to
     * be output through the 'Content-Length' header directive.
     */
    private function outputFileHeader($fileSizeSpecified = TRUE) {
        $disposition = $this->isFileDisplayedInline ? "inline" : "attachment";
        $type = $this->isFileDisplayedInline
                ? $this->getContentType() : 'application/octet-stream';
        $downloadedFilename = is_null($this->filenameToDownload)
                ? basename($this->fileToDownload) : $this->filenameToDownload;
        header('Content-Type: ' . $type);
        header('Content-Disposition: ' . $disposition . '; filename="'.$downloadedFilename.'"');
        if (!$this->isFileDisplayedInline) {
            header('Content-Description: File Transfer');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            if ($fileSizeSpecified) {
                header('Content-Length: ' . filesize($this->fileToDownload));
            }
        }
    }
    
    /**
     * Outputs the file to download
     * @throws \ZDKException Thrown when the specified file does not exist
     */
    private function download() {
        $this->outputFileHeader();
        readfile($this->fileToDownload);
    }
    
    /**
     * Outputs the data as a CSV file
     */
    private function csv_output() {
        $this->outputFileHeader(FALSE);
        $data = $this->dataToDownloadAsCsv;
        $out = fopen('php://output', 'w');
        foreach ($data as $value) {
            fputcsv($out, \Convert::valuesToAnsi($value), LC_LOCALE_CSV_SEPARATOR);
        }
        fclose($out);
    }

}
