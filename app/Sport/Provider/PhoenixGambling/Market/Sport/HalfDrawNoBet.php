<?php namespace App\Sport\Provider\PhoenixGambling\Market\Sport;

use App\Sport\Provider\PhoenixGambling\PhoenixGamblingSportMarketHandler;
use App\Sport\Provider\SportGameSnapshot;
use App\Sport\Provider\SportMarketTranslation;

class HalfDrawNoBet extends PhoenixGamblingSportMarketHandler {

  function isHandling(string $market, string $runner): bool {
    return str_contains($market, "half - draw no bet");
  }

  function isWinner(string $runner, SportGameSnapshot $snapshot): string {
    $winner = $this->getData($snapshot->id())->match()->period($this->extract($snapshot->market(), 'half'))->winner();
    $game = $this->findHistoricGame($snapshot->id());

    if($winner === 'draw') return $this->refund();
    if($winner === 'home' && $runner === $game->home) return $this->win();
    if($winner === 'away' && $runner === $game->away) return $this->win();

    return $this->lose();
  }

  function translation(string $market, string $runner): SportMarketTranslation {
    return (new SportMarketTranslation())->market('sport.market.sport.halfDrawNoBet', [ 'half' => $this->extract($market, 'half') ])->runner($runner);
  }

}
