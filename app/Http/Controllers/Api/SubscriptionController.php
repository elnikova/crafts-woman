<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subscription\StoreSubscriptionRequest;
use App\Http\Requests\Subscription\UpdateSubscriptionRequest;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SubscriptionController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return SubscriptionResource::collection(
            Subscription::query()->latest()->paginate(20)
        );
    }

    public function store(StoreSubscriptionRequest $request): JsonResponse
    {
        $subscription = Subscription::create($request->validated());

        return response()->json([
            'subscription' => new SubscriptionResource($subscription),
        ], 201);
    }

    public function show(Subscription $subscription): JsonResponse
    {
        return response()->json([
            'subscription' => new SubscriptionResource($subscription),
        ]);
    }

    public function update(UpdateSubscriptionRequest $request, Subscription $subscription): JsonResponse
    {
        $subscription->update($request->validated());

        return response()->json([
            'subscription' => new SubscriptionResource($subscription),
        ]);
    }

    public function destroy(Subscription $subscription): JsonResponse
    {
        $subscription->delete();

        return response()->json(['message' => 'Subscription deleted.']);
    }
}
