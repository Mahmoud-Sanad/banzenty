<?php

namespace App\Policies;

use App\Models\Station;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class StationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        if(Gate::allows('station_access') || Gate::allows('limited_station_access'))
            return true;

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Station  $station
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Station $station)
    {
        if(Gate::allows('station_show'))
            return true;

        if(Gate::allows('limited_station_show') && $user->stations->contains($station))
            return true;

        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        if(Gate::allows('station_create') || Gate::allows('limited_station_create'))
            return true;

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Station  $station
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Station $station)
    {
        if(Gate::allows('station_edit'))
            return true;

        if(Gate::allows('limited_station_edit') && $user->stations->contains($station))
            return true;

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Station  $station
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Station $station)
    {
        if(Gate::allows('station_delete'))
            return true;

        if(Gate::allows('limited_station_delete') && $user->stations->contains($station))
            return true;

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Station  $station
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Station $station)
    {
        if(Gate::allows('station_delete'))
            return true;

        if(Gate::allows('limited_station_delete') && $user->stations->contains($station))
            return true;

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Station  $station
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Station $station)
    {
        if(Gate::allows('station_delete'))
            return true;

        if(Gate::allows('limited_station_delete') && $user->stations->contains($station))
            return true;

        return false;
    }
}
