<?php

namespace App\Http\Controllers;

use App\Models\Candidat;
use App\Models\Event;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidatsController extends Controller
{
    /**
     * Liste des candidats.
     */
    public function index()
    {
        $candidats = Candidat::with('event')->get(); // Récupère tous les candidats avec l'événement associé
        return view('candidats.index', compact('candidats'));
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        $events = Event::all(); // Récupère tous les événements pour les afficher dans un select
        return view('candidats.create', compact('events'));
    }

    /**
     * Enregistrer un nouveau candidat.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'birthday' => 'nullable|date',
            'phoneNumber' => 'required|string|max:20',
            'sexe' => 'required|in:masculin,féminin',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'idEvent' => 'required|exists:events,id',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $photoPath = $file->storeAs('candidats/photos', $filename, 'public');
        }

        $profilePath = null;
        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $filename = time() . '_' . $file->getClientOriginalName();
            $profilePath = $file->storeAs('candidats/profiles', $filename, 'public');
        }

        Candidat::create([
            'name' => $request->name,
            'birthday' => $request->birthday,
            'phoneNumber' => $request->phoneNumber,
            'sexe' => $request->sexe,
            'photo' => $photoPath,
            'profile' => $profilePath,
            'idEvent' => $request->idEvent,
        ]);

        return redirect()->route('candidats.index')->with('success', 'Candidat créé avec succès.');
    }


    /**
     * Formulaire d’édition.
     */
    public function edit($id)
    {
        $candidat = Candidat::findOrFail($id);
        $events = Event::all();
        return view('candidats.create', compact('candidat', 'events'));
    }

    /**
     * Mettre à jour le candidat.
     */
    public function update(Request $request, $id)
    {
        $candidat = Candidat::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'birthday' => 'nullable|date',
            'phoneNumber' => 'required|string|max:20',
            'sexe' => 'required|in:masculin,féminin',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'idEvent' => 'required|exists:events,id',
        ]);

        if ($request->hasFile('photo')) {
            if ($candidat->photo && Storage::disk('public')->exists($candidat->photo)) {
                Storage::disk('public')->delete($candidat->photo);
            }
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $candidat->photo = $file->storeAs('candidats/photos', $filename, 'public');
        }

        if ($request->hasFile('profile')) {
            if ($candidat->profile && Storage::disk('public')->exists($candidat->profile)) {
                Storage::disk('public')->delete($candidat->profile);
            }
            $file = $request->file('profile');
            $filename = time() . '_' . $file->getClientOriginalName();
            $candidat->profile = $file->storeAs('candidats/profiles', $filename, 'public');
        }

        $candidat->update([
            'name' => $request->name,
            'birthday' => $request->birthday,
            'phoneNumber' => $request->phoneNumber,
            'sexe' => $request->sexe,
            'idEvent' => $request->idEvent,
        ]);

        return redirect()->route('candidats.index')->with('success', 'Candidat mis à jour avec succès.');
    }


    /**
     * Supprimer un candidat.
     */
    public function destroy($id)
    {
        $candidat = Candidat::findOrFail($id);

        if ($candidat->photo) {
            Storage::delete($candidat->photo);
        }
        if ($candidat->profile) {
            Storage::delete($candidat->profile);
        }

        $candidat->delete();

        return redirect()->route('candidats.index')->with('success', 'Candidat supprimé avec succès.');
    }

    public function votes()
    {
        $votes = Vote::with('candidat')->get();

        return view('candidats.vote', compact('votes'));
    }
}
