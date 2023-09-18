<?php
namespace App\Exceptions;
use App\Models\PagesTranslations;
use App\Models\Settings;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];
    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];
    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
        $this->renderable(function (Exception $e, $request) {
            $selected_page = Settings::where('setting', 'page')->pluck("value")->first();
            if ($this->isHttpException($e)) {
                switch (intval($e->getStatusCode())) {
                        // not found
                    case 404:
                        $pagetranslation = PagesTranslations::where("page_id", $selected_page)->where('status', 1)->first();
                        if ($selected_page != 0) {
                            return redirect()->route($pagetranslation->link);
                        } else {
                            return redirect()->route('front.page-notfound');
                        }
                        break;
                        // internal error
                    default:
                        return $this->renderHttpException($e);
                        break;
                }
            }
        });
    }
}
