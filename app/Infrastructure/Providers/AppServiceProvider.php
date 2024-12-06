<?php

namespace App\Infrastructure\Providers;

use App\Application\Services\EmailService;
use App\Application\Services\UserService;
use App\Domain\Repositories\AuthorRepository;
use App\Domain\Repositories\BookRepository;
use App\Domain\Repositories\LoanRepository;
use App\Domain\Repositories\UserRepository;
use App\Domain\Services\AuthorService;
use App\Domain\Services\BookService;
use App\Domain\Services\LoanService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(LoanService::class, function ($app, $params) {

            return new LoanService(
                request()->all(),
                $app->make(LoanRepository::class),
                $app->make(EmailService::class),
            );
        });

        $this->app->bind(AuthorService::class, function ($app, $params) {

            return new AuthorService(
                request()->all(),
                $app->make(AuthorRepository::class),
            );
        });

        $this->app->bind(BookService::class, function ($app, $params) {

            return new BookService(
                request()->all(),
                $app->make(BookRepository::class),
            );
        });

        $this->app->bind(UserService::class, function ($app) {
            return new UserService(
                request()->all(),
                $app->make(UserRepository::class),
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
