<?php
// src/Components/RandomNumberComponent.php
namespace App\Twig;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent('random_number')]
class RandomNumberComponent
{
  use DefaultActionTrait;
  
  #[LiveProp(writable: true)]
  public int $max = 1000;

  public function getRandomNumber(): int
  {
      return rand(0, $this->max);
  }
}
?>