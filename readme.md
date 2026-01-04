it's a saas website where any co-curricular activity club of an university can showcase their work and growth. currently the website is showing a science club of Mawlana Bhashani Science & Technnology University (MBSTU). here from the admin panel the admin can manage the full website. mosst of the clubs in most of the universities in bangladesh never have a website to them and the members are there for lot of activity and dedication does the club works but not get a national standard portfoliuo by the club. this website SaaS will give such ann opportunity to the club that ensuress the national and international assurity and values of the club and memberss of the club/organisation of Bangladesh (public/private universities). the members can also get trustworthy certificates over fixed type of templates and Professor approved certificates at all.

the website mainly showcases the club/organization portfolio, can add about them content their excellences, mission, vision and values from backend 'Website Settings' page.


Certificate Ticketing System:
The website has a certificate generating system in it. here the members of the club can come and apply for their club certificate. If anyone applies for a certificate then the admin will get an email to his email account attached with the websites admin panel. then he will login to the website and verify the certificate and will see if the certificate is from a valid participant or not. if it is then admin will verify it and if not then he can add Declinned statuss and user will be notified why they are declined with their certificate. And if the certificate needs any revision then also the usser can see the reasonn why it is put under revison. and the user can resubmit his details in such cases. If the certificate is verified then it will send email to the sselected advisor and the selected advisor then will login to their advisor/moderator panel. they will only see the assigned certificate applications to them. the advisor will only approve the certificate. and once the certificate is approved the certificate willbe sent to the applicant email instantly. So this is how the certificate ticketing system works.
In the admin panel there is a page named 'Applications' tab. from there admin can see all the applications from the applicants and then he can choose the application from the table of applications. there will be data and model like below:
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apply extends Model
{
    protected $fillable = [
        'applicant_name',
        'email',
        'designation',
        'member_since',
        'member_till',
        'impact',
        
        'certificate_text',
        'certificate_status',
        'template_id',
        'issued_by',
        'note',
        'issue_date',

        'certificate_positions',
        'certificate_file',
    ];
}


from where the admin will see the specific details of the application and here to the sspecifc application page there is a certificate setup section in the website. where admin can set user main contributions and acheivements inn a customised format (bold, italic, underlined) that will be shown as the details in the certificate. admin can change or select any template for the certificate and can set the text postions. There will be applicant_name, certificate_text, advisor name, signature and president name and ignature in the certificate. According to the choosen template the positions will need to be changed and once the admin ssaves the page the postionss also saves and it is dowloaded as a image file in the website folder and keeps record in the database. then when advisor approves, this image file sends to the applicant. Admin can change the certificate status and add the notest here as well. if verified then goess an email to advisor, if in revision or declined then sendss an email to the applicant about the note and case.

Templates Page:
So for certificates we need certificate templates. in the website here is also a platform where admin can create certificate template and can download the certificate template and upload with a certificate template name and keep the record with the database of the website. Thi model below is using for this:
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $fillable = [
        'template_name',
        'template_image',
    ];
}

where admin will add a design on clicking to the 'Design A Template' button of the Templates page. here in the template generation page user will see a canvas, where user can add images/logos of the organisation. can add shapes, lines, squares, texts and can change the sizes and rotate them. admin can also let a shape come to the front or go to the back. An allout feature full advanced system for creating certificate template. So for creating certificate the admin can rely on the software, don;t need to go to another website service at all. after creating the template admin will download and save it to his pc and then will upload as a new template with a new template name in the webssite. So when creating the certificate from the applications this template will also suggesst and admin can set the certificate over the newly created template. Also if admnin feels he will use another template created from any different platform then also he can do so, taking the certificate template picture from a different website he can push in here and upload to the website as a template.






Advisor & moderators in Admin panel:
The admin can set advisor profiles, their name email password career details and role to the club. there are two roles, Advisor & Moderator. Admin can set a Professor the role they want. Advisor will be shared with these email and password and they can login and can change these details from their advisor panel. Advisor creation is for multiple use cases. 
1) To showcase the advisor/moderator the profesors are with the club/organissation,
2) the advisors will be shown in the certificate section 
3) adviorss will have the only power to approve a certificate, as they can set their signature in the website and without their convinience no one can get any certificate at all, so it will add authenticity to the club/organisation member.
4) advisors (professors) jointment will make it more special the club in an ionternational standard that will help the students to gain more faith while applying for study abroad!


Team:
here will be the important members of the club added. Admin can set key and important user profiles from admin panel, user image, name, department, club role, membership period and a small and a long details about the member will be written to the website which will be shown in the website main pages. where the specific permalink of the member will be worked as a portfolio link of that club memeber.
In the admin panel admin can set a new member and also have CRUD facility herte, thuss he can remove, add, update any member. this is the laravel temmate model below:
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'teammate_image',
        'teammate_name',
        'department',
        'designation',
        'membership_period',
        'small_desc',
        'details',
        'facebook_link',
        'linkedin_link',
    ];
}

Here with the member image visitors can know about the member more specifically as well.


FAQs & Gallery:
there will be FAQs & gallery section those will showcase the website the brand/organissation growth and work history that what they did throughout the years, the faqs will set the questions those needed to ask by any of the visitors. Gallery has CRUD option and we can set image positions from the admin panel as well and thus we can maintain which imaghe to sshow first and which to next and which to show at last. Also has a feature to Show/Hide any image. thus any image might be uploaded but will be shown later on.


Website Settings:
This page is one of the key pages in the adminn panel. here the president signature, name will be set. thus wehn issuing any certificate the current president name and signature will be shown in the website certificatess. trhe certificate will hold president sign and the advisor/moderator signs. President sign will come from the webssite settings page and advisor sign will come from the signature added by the advisor on his profile. 
The werbsite settings page has fb, insstagram, twitter, linnkedin links and facebook page iframe tag to add in the pagew that will show the entire surface website. there will be new members and new members can be needed new type of joining link. so we have the option here for adding a google form or anyother form link that will be added in the settings page and then in the surface website users will be shown to the link and will be redirected to that link when they want top join the club/orgaanisation. this page has About us, Terms of Conditions, Code of Conduct/Club constitution, Privacy policy content adding options in this page. There are richtexteditor (Quill editor) is used that providess a rich text content for the website articale writing.



Executive Commitee Section:
This is annother important page where admin will be able to set new commitee name, and period and will be able to upload commitee images of the club. thus visitors can knos about the most recent commitee of the club and get a broad knnowledge about the club as well.


Admin Dashboard:
Here the website main stats are shown in the website. Total advissorss, total main team members, total club commitiees, total applications, total images in the gallery, total faqs are shown here.
A very iomportant part of the dashboard is it showss the daily, weekly, mopnthly, yearly and overall page view stats as a bar chart. which is a very iuseful feature. thus admin can see which page is shown by visiotrs most. So they can apply for google adsense later on as well. So another ssource of income can be generated to the website as well. This statistics will help the website a lot lot more.


Admin Settings Page:
From here admin can change the website email annd password with OTP verification that will have to done within 10 minute cause after this interval the OTP expires, so high security is ensured here. 
Here admin cann set appearance as well that on which mode he wants to see the admin panel, on dark or light or system preference. So useful.



Another part of the deep web is the Advissor/Moderator panel inn the website. the login link is '/advisor/login'
Advisor Panel:
here from the shared login credentials, the (emaail and password created by the admin for the advisor) advisor can login. he will be shownn errors if any wrong trial occurs, will be shown wrong email and wrong password in time of wrong email error or wrong password error. after loggedinn he will be redirected to advisor dashboard. where advisor will only see the Certificate Applications records as a table. the professors are busy in their regular life, so they donnn't have much time in hands thus their work load in the website is very minnimal. they just will see the certificates they need to approve and just will click to the email sent to them and in the email link when they click they will be logged in to the website (if they already loggedin ele will asked to login first) then will see the popup and just click to approve buttonn and certificate will be sent to the applicant as an email. On clicking to 'View Details' advisor cann be able to see the lists and then can approve the certificate from the popup.
Advisor Profile:
In the advisor profile ection advisors can change his signature, image, hjis department, designation, his facebook link, linkedin linnk, researchgate and google scholar link and also can add his portfolio website linnk in the webssite. The advisor cann also change his email and pasword if he wants. He can directly do this no otp verification needed, if any advisor loses access then admin can gain back that as well. 





Surface Website:
Though the deep web has the most work inside. the surface web has some minimal works.
Homepage: 
this shows the website landing page, very first the hero section of the organisation. where is also a Club joining link that is dynamic from website settings admin panel page. Then it also shows active members, evemts organised, research projects and awards it has won.
Then in next section it will tell about the club and it's small abut details. then the club journey, founding date ass well. and then what they do mission vission and values. After this in next sections the advisors created from the admin panel will be shown in the website that which professors are attached with the organiprofessorssation. next will sshow the team section. then the commitee section where user will see the comitees and can click to onne image and can open in large screen and like a gellery they can see the next and previous images of that commitee. 

next comess the FAQ and facebook page showing, where user can see the frequently asked wuestions and also the facebook page and can see the latest works done by the club as the clubs are mostly active on facebook thius facebook page is mostly vcommon and the feeds are showing in the website simple as well here in the website.
Next comes the gallery section where user will see the images acccording to positionss and where status=='show' from the laravel backend model.


Next comes the Contact section of the website. Most of the time in websites there is a way like the contact page is like an open port of a website for attack. So here to resolve the attacks when someone tries to send an email he has to firsst verify that email with OTP, thus fake emails are not sent easily. then after verifying then the visitor can send an email to the club only. here the club location, address, phone email, and social links are also shared.


Then a section that will ask the users to track or aplly for a membership certificate.


The headers and footers of the website holds the website surface page linnks and short information about the club. and easy access to, home,Conntact, Apply,Track Application, Priovacy policy, terms, constitution and if logged in as advisor then the profile and dashbaord link as well.



Apply For Certificate page: 
This is one of the important page of the website. which model is below:
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apply extends Model
{
    protected $fillable = [
        'applicant_name',
        'email',
        'designation',
        'member_since',
        'member_till',
        'impact',
        
        'certificate_text',
        'certificate_status',
        'template_id',
        'issued_by',
        'note',
        'issue_date',

        'certificate_positions',
        'certificate_file',
    ];
}



This website visiotrs comes and place their applicant name, email, designation, membership starting and ending date as input fields and a textarea to type what his impact was and what he have done for the club/organisation. Here he will add these details thus admin however is in current commitee can verify and check the details and process the certificate for thiss applicant.


Track Certificate page:
Though via email the applicants get notification yet they might delete the email or might not sent to them or might be any issue with the email or any case might occur. So the user can track the certificate from this page as well. the applicant just need to type his email and submit and then he can see the statuss of the certificate and also can see if certificate is declined or in recvision and then he can re apply for this. If the certificate is approved then he can see the details and can download the certificate from the link as well. and anyone can track and download his certificate from this link simply. so for certificate getting it has two ways, email way and website track page way. very convinient for the visiotrs and members of the website.


About Us Page:
This page shows a article about the club that comes dynamically from the website admin panel. It has a about section where the short details of the club is shared as well.
Then the distinguished advisors assigned with the website and then the temmates of the website as well.





the website has Privacy policy, terms and condiotions And Constitution page in it as well which show specific articles as well from the website backend.


Clicking to a team mate visiot can see the epcifci profile of a temmate and gain broad knowledge about the member as well. which permalinnk is: '/teammate/member_id/member_name'











Technology: 
the website is built with Laravel as Backend. Laravel 12 (latesst laravel version), Eloquennnt ORM, MVC pattern
Inertia is used to make connections with frontend and backend.
Frontend: Vue.JS (vue 3 latest vue versionn), TypeScript (latest), Tailwind (Tailwind 4 latest tailwind version) & Custom Media CSS, HTML, shadcn/ui is used as well.
Database: sqlite


It has middlewares in it for authentication.


for Inertia thiss middleware:
<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        [$message, $author] = str(Inspiring::quotes()->random())->explode('-');

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'quote' => ['message' => trim($message), 'author' => trim($author)],
            'auth' => [
                'user' => $request->user(),
                'advisor' => Auth::guard('advisor')->user(),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
            'flash' => [ // for showing success error messages as sweet alert!
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],
        ];
    }
}



For advisor this middleware:
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdvisorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('advisor')->check()) {
            return redirect()->route('advisor_panel.login_page')->with('error', 'Please login to continue');
        }

        return $next($request);
    }
}



For statistics this middleware is used:
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Statistic;

class StatisticsMeasure
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        if ($request->isMethod('GET') && $response->getStatusCode() === 200) {
            $this->recordStatistics($request);
        }
        
        return $response;
    }
    

    protected function recordStatistics(Request $request): void
    {
        $pageUrl = $request->fullUrl();
        Statistic::create([
            'page_url' => $pageUrl,
        ]);
    }
}





the middleware asssign page:
bootstrap/app.php:
<?php

use App\Http\Middleware\AdvisorMiddleware;
use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\StatisticsMeasure;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);

        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);


        // custom new middlewares
        $middleware->alias([
            'advisor_auth' => AdvisorMiddleware::class,
            'statistics' => StatisticsMeasure::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();





For Footer global values we use app/Providers/AppserviceProvider.php:
<?php

namespace App\Providers;

use App\Models\Advisor;
use App\Models\WebSettings;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $contact_email = WebSettings::find(1)->contact_email;
        $phone_number = WebSettings::find(1)->phone_number;
        $facebook_link = WebSettings::find(1)->facebook_link;
        $twitter_link = WebSettings::find(1)->twitter_link;
        $instagram_link = WebSettings::find(1)->instagram_link;
        $linkedin_link = WebSettings::find(1)->linkedin_link;


        View::share('contact_email', $contact_email);
        View::share('phone_number', $phone_number);
        View::share('facebook_link', $facebook_link);
        View::share('twitter_link', $twitter_link);
        View::share('instagram_link', $instagram_link);
        View::share('linkedin_link', $linkedin_link);


        Inertia::share([
            'webSettings' => [
                'contact_email' => $contact_email,
                'phone_number' => $phone_number,
                'facebook_link' => $facebook_link,
                'twitter_link' => $twitter_link,
                'instagram_link' => $instagram_link,
                'linkedin_link' => $linkedin_link,
            ]
        ]);
    }
}





the website main routes are routes/web.php
<?php

use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\AdminWebTasksController;
use App\Http\Controllers\AdvisorActionsController;
use App\Http\Controllers\AdvisorController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Committee;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\WebPagesController;
use Illuminate\Support\Facades\Route;


Route::get('/', [WebPagesController::class, 'homePage'])->name('home')->middleware('statistics');
Route::get('/teammate/{id}/{name}', [WebPagesController::class, 'teammateProfile'])->name('teammateProfile')->middleware('statistics');
Route::get('/about-us', [WebPagesController::class, 'aboutUs'])->name('aboutUs')->middleware('statistics');
Route::get('/privacy-policy', [WebPagesController::class, 'privacy'])->name('privacy')->middleware('statistics');
Route::get('/terms-and-conditions', [WebPagesController::class, 'terms_conditions'])->name('terms_conditions')->middleware('statistics');
Route::get('/code-of-conduct', [WebPagesController::class, 'code_of_conduct'])->name('code_of_conduct')->middleware('statistics');
Route::get('/application', [ApplicationController::class, 'application'])->name('application')->middleware('statistics');
Route::get('/track-certificate', [ApplicationController::class, 'track_application'])->name('track_page')->middleware('statistics');



Route::post('/apply-certificate', [ApplicationController::class, 'apply_certificate'])->name('apply_certificate');
Route::post('/track-certificate', [ApplicationController::class, 'track_result'])->name('track_result');
Route::post('/download-certificate', [ApplicationController::class, 'download_certificate'])->name('download_certificate');

Route::post('/email-otp', [WebPagesController::class, 'contactOTP'])->name('contactOTP');
Route::post('/verify-and-send-email', [WebPagesController::class, 'verifyAndSendEmail'])->name('verifyAndSendEmail');



Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [WebPagesController::class, 'adminDashboard'])->name('dashboard');



    Route::get('/applications', [ApplicationController::class, 'applications'])->name('applications');
    Route::get('/applications/{id}/{applicant_name}', [ApplicationController::class, 'validate_application'])->name('validate_application');
    Route::post('/application/update', [ApplicationController::class, 'updateApplication'])->name('update_application');
    Route::post('/application/delete', [ApplicationController::class, 'delete_application'])->name('delete_application');



    Route::get('/advisors-and-moderators', [AdvisorController::class, 'index'])->name('advisors.index');
    Route::get('/advisors-and-moderators/create', [AdvisorController::class, 'create'])->name('advisors.create');
    Route::post('/advisors-and-moderators', [AdvisorController::class, 'store'])->name('advisors.store');
    Route::get('/advisors-and-moderators/{id}/edit', [AdvisorController::class, 'edit'])->name('advisors.edit');
    Route::post('/advisors-and-moderators/update', [AdvisorController::class, 'update'])->name('advisors.update');
    Route::delete('/advisors-and-moderators/{id}', [AdvisorController::class, 'destroy'])->name('advisors.destroy');




    Route::get('/manage-team', [TeamController::class, 'index'])->name('team.index');
    Route::get('/manage-team/create', [TeamController::class, 'create'])->name('team.create');
    Route::post('/manage-team/store', [TeamController::class, 'store'])->name('team.store');
    Route::get('/manage-team/{id}', [TeamController::class, 'edit'])->name('team.edit');
    Route::post('/manage-team/update', [TeamController::class, 'update'])->name('team.update');
    Route::post('/manage-team/destroy', [TeamController::class, 'destroy'])->name('team.destroy');



    Route::get('/website-settings', [AdminWebTasksController::class, 'websiteSettings'])->name('web_settings.index');
    Route::post('/website-settings/update', [AdminWebTasksController::class, 'updateWebSettings'])->name('web_settings.update');


    Route::get('/our-committee', [Committee::class, 'index'])->name('committees.index');
    Route::get('/our-committee/create', [Committee::class, 'create'])->name('committees.create');
    Route::post('/our-committee/store', [Committee::class, 'store'])->name('committees.store');
    Route::get('/our-committee/{id}', [Committee::class, 'edit'])->name('committees.edit');
    Route::post('/our-committee/update', [Committee::class, 'update'])->name('committees.update');
    Route::post('/our-committee/delete', [Committee::class, 'destroy'])->name('committees.destroy');




    Route::get('/faqs', [FAQController::class, 'index'])->name('faqs.index');
    Route::post('/faqs/store', [FAQController::class, 'store'])->name('faqs.store');
    Route::put('/faqs/update', [FAQController::class, 'update'])->name('faqs.update');
    Route::delete('/faqs/{id}', [FAQController::class, 'destroy'])->name('faqs.destroy');




    Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
    Route::post('/gallery/store', [GalleryController::class, 'store'])->name('gallery.store');
    Route::post('/gallery/update', [GalleryController::class, 'update'])->name('gallery.update'); // Changed to POST to support file uploads
    Route::post('/gallery/update-positions', [GalleryController::class, 'updatePositions'])->name('gallery.updatePositions');
    Route::delete('/gallery/{id}', [GalleryController::class, 'destroy'])->name('gallery.destroy');







    Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
    Route::get('/templates/create', [TemplateController::class, 'create'])->name('templates.create');
    Route::post('/templates/store', [TemplateController::class, 'store'])->name('templates.store');
    Route::get('/templates/{id}', [TemplateController::class, 'edit'])->name('templates.edit');
    Route::post('/templates/update', [TemplateController::class, 'update'])->name('templates.update');
    Route::delete('/templates/{id}', [TemplateController::class, 'destroy'])->name('templates.destroy');




    Route::post('/admin/profile/update-name', [AdminProfileController::class, 'updateName'])->name('admin.profile.update-name');
    Route::post('/admin/profile/request-email-change', [AdminProfileController::class, 'requestEmailChange'])->name('admin.profile.request-email-change');
    Route::post('/admin/profile/verify-email-otp', [AdminProfileController::class, 'verifyEmailOtp'])->name('admin.profile.verify-email-otp');
    Route::post('/admin/profile/request-password-change', [AdminProfileController::class, 'requestPasswordChange'])->name('admin.profile.request-password-change');
    Route::post('/admin/profile/verify-password-otp', [AdminProfileController::class, 'verifyPasswordOtp'])->name('admin.profile.verify-password-otp');
});





// advisor portal 
Route::get('/advisor/login', [AdvisorActionsController::class, 'advisorLogin'])->name('advisor_panel.login_page');
Route::post('/advisor/login', [AdvisorActionsController::class, 'verifyAndLogin'])->name('advisor_panel.login');

// Advisor portal - Protected routes
Route::middleware(['advisor_auth'])->group(function () {
    Route::get('/advisor/dashboard', [AdvisorActionsController::class, 'advisorDashboard'])->name('advisor_panel.dahsboard');
    Route::post('/advisor/approve-application', [AdvisorActionsController::class, 'approveApplication'])->name('advisor_panel.approve');

    Route::get('/advisor/profile', [AdvisorActionsController::class, 'advisorProfile'])->name('advisor_panel.profile');
    Route::post('/advisor/update-profile', [AdvisorActionsController::class, 'updateProfile'])->name('advisor_panel.update_profile');

    Route::post('/advisor/logout', [AdvisorActionsController::class, 'logout'])->name('advisor_panel.logout');
});



require __DIR__ . '/settings.php';



the package.json file that shows the installed files and packages are:
{
    "$schema": "https://www.schemastore.org/package.json",
    "private": true,
    "type": "module",
    "scripts": {
        "build": "vite build",
        "build:ssr": "vite build && vite build --ssr",
        "dev": "vite",
        "format": "prettier --write resources/",
        "format:check": "prettier --check resources/",
        "lint": "eslint . --fix"
    },
    "devDependencies": {
        "@eslint/js": "^9.19.0",
        "@laravel/vite-plugin-wayfinder": "^0.1.3",
        "@tailwindcss/vite": "^4.1.11",
        "@types/fabric": "^5.3.10",
        "@types/html2canvas": "^0.5.35",
        "@types/node": "^22.13.5",
        "@vitejs/plugin-vue": "^6.0.0",
        "@vue/eslint-config-typescript": "^14.3.0",
        "concurrently": "^9.0.1",
        "eslint": "^9.17.0",
        "eslint-config-prettier": "^10.0.1",
        "eslint-plugin-vue": "^9.32.0",
        "prettier": "^3.4.2",
        "prettier-plugin-organize-imports": "^4.1.0",
        "prettier-plugin-tailwindcss": "^0.6.11",
        "typescript": "^5.2.2",
        "typescript-eslint": "^8.23.0",
        "vite": "^7.0.4",
        "vue-tsc": "^2.2.4"
    },
    "dependencies": {
        "@inertiajs/vue3": "^2.1.0",
        "@types/three": "^0.182.0",
        "@types/uuid": "^10.0.0",
        "@vueup/vue-quill": "^1.2.0",
        "@vueuse/core": "^12.8.2",
        "chart.js": "^4.5.1",
        "class-variance-authority": "^0.7.1",
        "clsx": "^2.1.1",
        "fabric": "^7.1.0",
        "html-to-image": "^1.11.13",
        "html2canvas": "^1.4.1",
        "jspdf": "^3.0.4",
        "laravel-vite-plugin": "^2.0.0",
        "lodash": "^4.17.21",
        "lucide-vue-next": "^0.468.0",
        "quill": "^2.0.3",
        "reka-ui": "^2.6.1",
        "sweetalert2": "^11.26.17",
        "tailwind-merge": "^3.2.0",
        "tailwindcss": "^4.1.1",
        "three": "^0.182.0",
        "tw-animate-css": "^1.2.5",
        "uuid": "^13.0.0",
        "vue": "^3.5.13",
        "vue-chartjs": "^5.3.3",
        "vue-input-otp": "^0.3.2",
        "vue-sweetalert2": "^5.0.11"
    },
    "optionalDependencies": {
        "@rollup/rollup-linux-x64-gnu": "4.9.5",
        "@rollup/rollup-win32-x64-msvc": "4.9.5",
        "@tailwindcss/oxide-linux-x64-gnu": "^4.0.1",
        "@tailwindcss/oxide-win32-x64-msvc": "^4.0.1",
        "lightningcss-linux-x64-gnu": "^1.29.1",
        "lightningcss-win32-x64-msvc": "^1.29.1"
    }
}



It uses Laravel & Vite. the vite.config.ts file below:
import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        tailwindcss(),
        wayfinder({
            formVariants: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
});





The .env file has the main credentials and the email credentials which uses laravel built in Mail facade feature. the mail will send email from the server of the website.
these details: 
MAIL_DRIVER=sendmail
MAIL_HOST=server_mail_host
MAIL_PORT=587
MAIL_USERNAME=mail_where_sent_from
MAIL_PASSWORD=server_mail_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=mail_where_sent_from
MAIL_MAILER=smtp
MAIL_FROM_NAME="${APP_NAME}"


these details ensures a good communication for email.


The database are created using migrations according to the model names.

the codebase github repository link:
https://github.com/theCoder505/mbstu_science_club



website test url is: https://mbstusc.eksofts.xyz



