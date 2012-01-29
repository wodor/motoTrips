<?php
namespace WodorNet\MotoTripBundle;

class MotoTripEvents
{
    // const onTripCreate = 'trip.create';

    //  const onTripEdit = 'trip.edit';

    //   const onTripSignupCreate = 'tripSignup.create';

    const onTripSignupCreate = 'tripSignup.create';
    const onTripSignupApprove = 'tripSignup.approve';
    const onTripSignupDisapprove = 'tripSignup.disapprove';
    const onTripSignupReject = 'tripSignup.reject';
    const onTripSignupResign = 'tripSignup.resign';

}
