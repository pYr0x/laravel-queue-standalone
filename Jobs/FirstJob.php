<?php


namespace Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Jobs\Bus\Dispatchable;
//use Illuminate\Foundation\Bus\Dispatchable;




class FirstJob implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
//  use InteractsWithQueue, Queueable, SerializesModels;

  protected $foo;

  /**
   * Create a new job instance.

   */
  public function __construct($args = null) {
    $this->foo = $args;

  }

  /**
   * Execute the job.
   *
   */
  public function handle()
  {
    file_put_contents("foo.txt", $this->foo);
  }
}
