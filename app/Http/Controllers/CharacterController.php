<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;
use Validator;
use App\Models\Character;
use App\Models\Comparison;
use App\Services\API\BattleNet;

class CharacterController extends Controller
{
    public function getSavedCharacters()
    {
        $user = Auth::user();
        $user_id = $user->id;
        $characters = $user->characters->sortBy('id');
        return $characters;
    }
    
    public function saved()
    {
        $characters = CharacterController::getSavedCharacters();
        return view('saved', [
            'characters' => $characters
        ]);
    }
    
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'realm' => 'required',
            'class' => 'required',
            'specialization' => 'required'
        ]);
        
        if ($validation->fails())
        {
            return redirect($_SERVER['HTTP_REFERER']);
        }
        
        $character = Character::firstOrCreate([
            'user_id' => Auth::user()->id,
            'name' => $request->input('name'),
            'realm' => $request->input('realm'),
            'class' => $request->input('class'),
            'specialization' => $request->input('specialization')
        ]);
                
        return redirect('saved');
    }
    
    public function remove(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'character_id' => 'required|integer'
        ]);
        
        if ($validation->fails())
        {
            return redirect($_SERVER['HTTP_REFERER']);
        }
        
        $character = Character::find($request->input('character_id'));
        $character->delete();
        
        return redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function compare()
    {
        $characters = CharacterController::getSavedCharacters();
        return view('compare', [
            'characters' => $characters
        ]);
    }
    
    public function runComparison(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'character_ids' => 'required|array|min:2'
        ]);
        
        if ($validation->fails())
        {
            return redirect('compare')
                ->withInput()
                ->withErrors($validation->errors());
        }
        
        $characters = Character::find($request->input('character_ids'));
        $battlenet = new BattleNet();
        $profiles = [];
        
        foreach ($characters as $character)
        {
            $profiles[] = $battlenet->getCharacter($character->realm, $character->name);
        }
        
        
        $comparison = new \stdClass();
        $names = array();
        $charactersWithItem = array();
        
        foreach ($profiles as $profile)
        {
            if (!empty($profile->items->head))
            {
                $comparison->head[] = $profile->items->head->id;
                $names[$profile->items->head->id] = $profile->items->head->name;
                $charactersWithItem[$profile->items->head->id][] = $profile->name . '-' . $profile->realm;
            }
            
            if (!empty($profile->items->neck))
            {
                $comparison->neck[] = $profile->items->neck->id;
                $names[$profile->items->neck->id] = $profile->items->neck->name;
                $charactersWithItem[$profile->items->neck->id][] = $profile->name . '-' . $profile->realm;
            }
            
            if (!empty($profile->items->shoulder))
            {
                $comparison->shoulder[] = $profile->items->shoulder->id;
                $names[$profile->items->shoulder->id] = $profile->items->shoulder->name;
                $charactersWithItem[$profile->items->shoulder->id][] = $profile->name . '-' . $profile->realm;
            }
            
            if (!empty($profile->items->back))
            {
                $comparison->back[] = $profile->items->back->id;
                $names[$profile->items->back->id] = $profile->items->back->name;
                $charactersWithItem[$profile->items->back->id][] = $profile->name . '-' . $profile->realm;
            }
            
            if (!empty($profile->items->chest))
            {
                $comparison->chest[] = $profile->items->chest->id;
                $names[$profile->items->chest->id] = $profile->items->chest->name;
                $charactersWithItem[$profile->items->chest->id][] = $profile->name . '-' . $profile->realm;
            }
            
            if (!empty($profile->items->shirt))
            {
                $comparison->shirt[] = $profile->items->shirt->id;
                $names[$profile->items->shirt->id] = $profile->items->shirt->name;
                $charactersWithItem[$profile->items->shirt->id][] = $profile->name . '-' . $profile->realm;
            }
            
            if (!empty($profile->items->tabard))
            {
                $comparison->tabard[] = $profile->items->tabard->id;
                $names[$profile->items->tabard->id] = $profile->items->tabard->name;
                $charactersWithItem[$profile->items->tabard->id][] = $profile->name . '-' . $profile->realm;
            }
            
            if (!empty($profile->items->wrist))
            {
                $comparison->wrist[] = $profile->items->wrist->id;
                $names[$profile->items->wrist->id] = $profile->items->wrist->name;
                $charactersWithItem[$profile->items->wrist->id][] = $profile->name . '-' . $profile->realm;
            }
            
            if (!empty($profile->items->hands))
            {
                $comparison->hands[] = $profile->items->hands->id;
                $names[$profile->items->hands->id] = $profile->items->hands->name;
                $charactersWithItem[$profile->items->hands->id][] = $profile->name . '-' . $profile->realm;
            }
            
            if (!empty($profile->items->waist))
            {
                $comparison->waist[] = $profile->items->waist->id;
                $names[$profile->items->waist->id] = $profile->items->waist->name;
                $charactersWithItem[$profile->items->waist->id][] = $profile->name . '-' . $profile->realm;
            }
            
            if (!empty($profile->items->legs))
            {
                $comparison->legs[] = $profile->items->legs->id;
                $names[$profile->items->legs->id] = $profile->items->legs->name;
                $charactersWithItem[$profile->items->legs->id][] = $profile->name . '-' . $profile->realm;
            }
            
            if (!empty($profile->items->feet))
            {
                $comparison->feet[] = $profile->items->feet->id;
                $names[$profile->items->feet->id] = $profile->items->feet->name;
                $charactersWithItem[$profile->items->feet->id][] = $profile->name . '-' . $profile->realm;
            }
            
            if (!empty($profile->items->finger1))
            {
                $comparison->finger[] = $profile->items->finger1->id;
                $names[$profile->items->finger1->id] = $profile->items->finger1->name;
                $charactersWithItem[$profile->items->finger1->id][] = $profile->name . '-' . $profile->realm;
            }
            
            if (!empty($profile->items->finger2))
            {
                $comparison->finger[] = $profile->items->finger2->id;
                $names[$profile->items->finger2->id] = $profile->items->finger2->name;
                $charactersWithItem[$profile->items->finger2->id][] = $profile->name . '-' . $profile->realm;
            }
            
            if (!empty($profile->items->trinket1))
            {
                $comparison->trinket[] = $profile->items->trinket1->id;
                $names[$profile->items->trinket1->id] = $profile->items->trinket1->name;
                $charactersWithItem[$profile->items->trinket1->id][] = $profile->name . '-' . $profile->realm;
            }
            
            if (!empty($profile->items->trinket2))
            {
                $comparison->trinket[] = $profile->items->trinket2->id;
                $names[$profile->items->trinket2->id] = $profile->items->trinket2->name;
                $charactersWithItem[$profile->items->trinket2->id][] = $profile->name . '-' . $profile->realm;
            }
            
            if (!empty($profile->items->mainHand))
            {
                $comparison->mainHand[] = $profile->items->mainHand->id;
                $names[$profile->items->mainHand->id] = $profile->items->mainHand->name;
                $charactersWithItem[$profile->items->mainHand->id][] = $profile->name . '-' . $profile->realm;
            }
            
            if (!empty($profile->items->offHand))
            {
                $comparison->offHand[] = $profile->items->offHand->id;
                $names[$profile->items->offHand->id] = $profile->items->offHand->name;
                $charactersWithItem[$profile->items->offHand->id][] = $profile->name . '-' . $profile->realm;
            }
        }
        
        
        return view('result', [
            'comparison' => $comparison,
            'names' => $names,
            'characters' => $characters,
            'charactersWithItem' => $charactersWithItem
        ]);
    }
    
    public function deleteComparison(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'comparison_id' => 'required|integer'
        ]);
        
        if ($validation->fails())
        {
            return redirect($_SERVER['HTTP_REFERER']);
        }
        
        $comparison = Comparison::find($request->input('comparison_id'));
        $comparison->delete();
        
        return redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function getSavedComparisons()
    {
        $user = Auth::user();
        $user_id = $user->id;
        $comparisons = $user->comparisons->sortBy('id');
        return $comparisons;
    }
    
    public function viewSavedComparisons()
    {
        $comparisons = CharacterController::getSavedComparisons();
        
        foreach ($comparisons as $comparison)
        {
            $character_ids = json_decode($comparison->character_ids);
            $characters = Character::find($character_ids);
            $comparison->characters = $characters;
        }
        return view('saved-compare', [
            'comparisons' => $comparisons
        ]);
    }
    
    public function saveComparison(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'character_ids' => 'required|array|min:2'
        ]);
        
        if ($validation->fails())
        {
            return redirect($_SERVER['HTTP_REFERER']);
        }
        
        $comparison = Comparison::firstOrCreate([
            'character_ids' => json_encode($request->input('character_ids')),
            'user_id' => Auth::user()->id
        ]);
        
        return redirect('saved-compare');
    }
}
