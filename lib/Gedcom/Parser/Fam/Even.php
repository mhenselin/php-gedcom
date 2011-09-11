<?php
/**
 * php-gedcom
 *
 * php-gedcom is a library for parsing, manipulating, importing and exporting
 * GEDCOM 5.5 files in PHP 5.3+.
 *
 * @author          Kristopher Wilson <kwilson@shuttlebox.net>
 * @copyright       Copyright (c) 2010-2011, Kristopher Wilson
 * @package         php-gedcom 
 * @license         http://php-gedcom.kristopherwilson.com/license
 * @link            http://php-gedcom.kristopherwilson.com
 * @version         SVN: $Id$
 */

namespace Gedcom\Parser\Fam;

/**
 *
 *
 */
class Even extends \Gedcom\Parser\Component
{
    
    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        
        $even = new \Gedcom\Record\Fam\Even();
        
        if(isset($record[1]) && strtoupper(trim($record[1])) != 'EVEN')
            $even->type = trim($record[1]);
        
        $parser->forward();
        
        while(!$parser->eof())
        {
            $record = $parser->getCurrentLineRecord();
            $recordType = strtoupper(trim($record[1]));
            $currentDepth = (int)$record[0];
            
            if($currentDepth <= $depth)
            {
                $parser->back();
                break;
            }
            
            switch($recordType)
            {
                case 'TYPE':
                    $even->type = trim($record[2]);
                break;
                
                case 'DATE':
                    $even->date = trim($record[2]);
                break;
                
                case 'PLAC':
                    $plac = \Gedcom\Parser\Indi\Even\Plac::parse($parser);
                    $even->plac = $plac;
                break;
                
                case 'ADDR':
                    $even->addr = \Gedcom\Parser\Addr::parse($parser);
                break;
                
                case 'PHON':
                    $phone = \Gedcom\Parser\Phone::parse($parser);
                    $even->addPhone($phone);
                break;
                
                case 'CAUS':
                    $even->caus = trim($record[2]);
                break;
                
                case 'AGE':
                    $even->age = trim($record[2]);
                break;
                
                case 'AGNC':
                    $even->agnc = trim($record[2]);
                break;
                
                case 'HUSB':
                    $husb = \Gedcom\Parser\Fam\Even\Husb::parse($parser);
                    $even->husb = $husb;
                break;
                
                case 'WIFE':
                    $wife = \Gedcom\Parser\Fam\Even\Wife::parse($parser);
                    $even->wife = $wife;
                break;
                
                case 'SOUR':
                    $sour = \Gedcom\Parser\SourRef::parse($parser);
                    $even->addSour($sour);
                break;
                
                case 'OBJE':
                    $obje = \Gedcom\Parser\ObjeRef::parse($parser);
                    $even->addObje($obje);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    $even->addNote($note);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $even;
    }
}
