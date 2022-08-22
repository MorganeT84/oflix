<?php

namespace App\Utils;

/**
 * Slugify the given string
 * 
 * @param string $toBeSlugged
 * @return string
 */
class Slugger
{
  public function makeSlug(string $toBeSlugged): string
  {

    // on met en minuscule
    // on remplace les " " par des "-"
    $slug = str_replace(" ", "-", strtolower($toBeSlugged));

    return $slug;


  }
}
