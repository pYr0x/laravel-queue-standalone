<?php


namespace app\jobs;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use twentytwo\foundation\bus\Dispatchable;

class WriteFile implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected $text;

  /**
   * Create a new job instance.

   */
  public function __construct($args = null) {
    $this->text = $args;

  }

  /**
   * Execute the job.
   *
   */
  public function handle()
  {
    file_put_contents("foo.txt", $this->text);
  }
}
