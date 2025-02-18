<?php

namespace App\Http\Controllers;

use App\Models\Candidat;
use App\Models\Vote;
use Illuminate\Http\Request;

class HomeConroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Récupérer les candidats avec leurs votes, y compris la somme des votes.
        $candidats = Candidat::with(['event', 'votes'])
            ->withCount('votes') // Compte les votes pour chaque candidat
            ->withSum('votes', 'numbreOfVote') // Récupère la somme de `numbreOfVote` pour chaque candidat
            ->orderByDesc('votes_sum_numbreOfVote') // Trie les candidats par la somme totale de `numbreOfVote`
            ->get();
        return view('welcome', compact('candidats'));
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $candidat = Candidat::findOrfail($id);
        return view('candidatview', compact('candidat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function about()
    {
        return view('about');
    }

    public function vote($id, Request $request)
    {
        $candidat = Candidat::findOrFail($id);
        $numberVote = (int) $request->vote;
        $amountUnit = $candidat->event->unitAmount;
        $amount = $numberVote * $amountUnit;

        // Sauvegarder les informations du vote dans la session
        session([
            'vote_data' => [
                'idCandidat' => $candidat->id,
                'numberVote' => $numberVote,
                'amount' => $amount,
            ]
        ]);

        \FedaPay\FedaPay::setApiKey('sk_sandbox_Wf5kGyW99hsXB20VJa-TpVFb');
        \FedaPay\FedaPay::setEnvironment('sandbox');

        try {
            // Créer la transaction
            $transaction = \FedaPay\Transaction::create([
                'description' => 'Vote pour le concours de plaidoirie',
                'amount' => $amount,
                'currency' => ['iso' => 'XOF'],
                'callback_url' => route('payment.callback'), // URL de retour après paiement
                'customer' => [
                    'firstname' => $candidat->name,
                ]
            ]);

            // Rediriger l'utilisateur vers la page de paiement
            $token = $transaction->generateToken();
            return redirect($token->url);
        } catch (\Exception $e) {
            return redirect()->route('candidat', $candidat->id)->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }


    public function paymentCallback(Request $request)
    {
        try {
            \FedaPay\FedaPay::setApiKey('sk_sandbox_Wf5kGyW99hsXB20VJa-TpVFb');
            \FedaPay\FedaPay::setEnvironment('sandbox');

            $transaction = \FedaPay\Transaction::retrieve($request->id);

            if ($transaction->status === 'approved') {
                // Récupérer les infos stockées dans la session
                $voteData = session('vote_data');

                if ($voteData) {
                    // Enregistrer le vote dans la table `votes`
                    Vote::create([
                        'numbreOfVote' => $voteData['numberVote'],
                        'amount' => $voteData['amount'],
                        'idCandidat' => $voteData['idCandidat'],
                    ]);

                    // Retirer les données de la session une fois le vote enregistré

                    return redirect()->route('candidat', $voteData['idCandidat'])->with('success', 'Votre vote a été enregistré avec succès !');
                    session()->forget('vote_data');
                }

                return redirect()->route('home')->with('error', 'Une erreur est survenue.');
            } else {
                return redirect()->route('home')->with('error', 'Le paiement a échoué.');
            }
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }
}
