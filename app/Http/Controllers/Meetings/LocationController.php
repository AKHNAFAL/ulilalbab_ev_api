<?php

namespace App\Http\Controllers\Meetings;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LocationRequest;
use App\Http\Resources\LocationResource;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locations = Location::all();
        return LocationResource::collection($locations); // Use collection to transform all locations
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LocationRequest  $request)
    {
        $validated = $request->validated(); // Validated data is automatically retrieved from the validated method in LocationRequest
        $location = Location::create($validated); // Create the location with validated data
        return new LocationResource($location); // Return the newly created location as a resource
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
        return new LocationResource($location); // Show specific location as a resource
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LocationRequest   $request, Location $location)
    {
        $validated = $request->validated(); // Get validated data from LocationRequest
        $location->update($validated); // Update the location with validated data
        return new LocationResource($location); // Return the updated location as a resource
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        $location->delete(); // Delete the location
        return response()->json(['message' => 'Location deleted successfully'], 200); // Return success message
    }
}
