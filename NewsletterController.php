<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\Promotion;
use App\Models\Abonne;
use App\Http\Requests\NewsletterRequest\SubscribeFormRequest;
use App\Http\Requests\NewsletterRequest\PromotionsFormRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewsletterMail;
use App\Mail\NewsletterSubscriptionConfirmation;
use App\Mail\abonneMail;
use Ramsey\Uuid\Uuid;




class NewsletterController extends Controller
{

// methode qui permet d'enregistrer un abonnées dans la base de données
    public function storeSubscription(SubscribeFormRequest $request)
    {


    // Vérifier si l'abonné existe déjà dans la base de données
    $AbonneExiste = Abonne::where('email', $request->email)->first();

    if ($AbonneExiste) {
        
            return Redirect::back()->with('success', 'Abonnement enregistré avec succès !');
    }else
        {

            // généré automatuqeument un token pour les abonnées lors de leur abonnemnt a la newsletter
            $uuid = Uuid::uuid4();
            $unsubscribe_token = $uuid->toString();

            // enregistrement dans la base de données
            $abonnes = new Abonne($request->validated());
            $abonnes->unsubscribe_token = $unsubscribe_token;
            $abonnes->save();

            // envoi du mail de confirmation
            Mail::to($request->email)->send(new NewsletterSubscriptionConfirmation());


            return Redirect::back()->with('success', 'Abonnement enregistré avec succès !');
         }

    }


}
