<?php

use App\Http\Requests\ChirpStoreRequest;
use App\Models\Chirp;

use function InertiaVolt\Laravel\{render,post,put,delete};

render('', handler: static fn() => [
    'chirps' => Chirp::with('user:id,name')->latest()->get(),
])->name('index');

post('', handler: static function (ChirpStoreRequest $request) {
    $request->user()->chirps()->create($request->validated());
 
    return back();
})->name('store');

put(
    '{chirp}',
    handler: static function (ChirpStoreRequest $request, Chirp $chirp) {
        $chirp->update($request->validated());

        return back();
    },
)->can('update', 'chirp')->name('update');

delete(
    uri: '{chirp}',
    handler: static function (Chirp $chirp) {
        $chirp->delete();

        return back();
    },
)->can('delete', 'chirp')->name('destroy');

?>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm, Head } from '@inertiajs/vue3';
import Chirp from '@/Components/Chirp.vue';

defineProps(['chirps']);
 
const form = useForm({
    message: '',
});
</script>
 
<template>
    <Head title="Chirps" />
 
    <AuthenticatedLayout>
        <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            <form @submit.prevent="form.post(route('chirps.store'), { onSuccess: () => form.reset() })">
                <textarea
                    v-model="form.message"
                    placeholder="What's on your mind?"
                    class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                ></textarea>
                <InputError :message="form.errors.message" class="mt-2" />
                <PrimaryButton class="mt-4">Chirp</PrimaryButton>
            </form>

            <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">
                <Chirp
                    v-for="chirp in chirps"
                    :key="chirp.id"
                    :chirp="chirp"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>