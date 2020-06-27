<?php
    
    namespace MarwaDB;
    
trait SqlTrait
{
        
    /**
     * @param  string $text
     * @return string
     */
    public function removeWhiteSpace( string $text )
    {
        $text = preg_replace('/[\t\n\r\0\x0B]/', '', $text);
        $text = preg_replace('/([\s])\1+/', ' ', $text);
        $text = trim($text);
            
        return $text;
    }
}
