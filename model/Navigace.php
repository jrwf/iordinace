<?php

class Navigace 
{
  public function navActiove()
  {

      switch($_SERVER['REQUEST_URI']) {
          case '/home':
            $page_path = 'page-home';
            break;
          case '/ockovani':
            $page_path = 'page-ockovani';
            break;
      }

  }
}

