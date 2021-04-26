<script>
  import {Inertia} from '@inertiajs/inertia';
  import Icon, {Trash} from 'svelte-hero-icons';

  import Layout from '$layout/Layout.svelte';
  import Spinner from '$components/Spinner.svelte';
  import AreYouSure from '$components/Modals/AreYouSure.svelte';

  // State
  export let auth;

  let form = {
    name: auth.name,
    slug: auth.slug,
    github: auth.links?.github || null,
    paypal: auth.links?.paypal || null,
    patreon: auth.links?.patreon || null,
    support: auth.links?.support || null
  }

  let submitting = false;
  let errors;
  const submit = () => {
    Inertia.put('/user', form, {
      preserveScroll: true,
      onStart() {
        submitting = true;
      },
      onError(err) {
        errors = err;
      },
      onFinish() {
        submitting = false;
      },
      onSuccess() {
        errors = null;
      }
    })
  }

  let deleteModal = false;
  const destroy = () => {
    Inertia.delete('/user');
  }
</script>

<Layout title="Profile settings">
  <div class="wrap">
    <main class="my-12">
      <div class="mb-12">
        <h3 class="font-display mb-2">Account settings</h3>
        <section class="bg-gray-50 dark:bg-gray-800 rounded overflow-hidden p-4">
          <div class=" mb-4">
            <p class="text-sm mb-2">Username</p>
            <input type="text" class="input input-secondary w-full {errors?.name ? '!border-red-500 hover:!border-red-400' : ''}" bind:value={form.name}>
            {#if errors?.name}
              <small class="text-red-500">{errors.name}</small>
            {/if}
          </div>
          <div>
            <p class="text-sm">Slug</p>
            <small class="block mb-2 text-gray-500 dark:text-gray-500">This is your unique identifier and is used when viewing your profile. (users/{form.slug})</small>
            <input type="text" class="input input-secondary w-full {errors?.slug ? '!border-red-500 hover:!border-red-400' : ''}" bind:value={form.slug}>
            {#if errors?.slug}
              <small class="text-red-500">{errors.slug}</small>
            {/if}
          </div>
        </section>
      </div>
      {#if auth.roles.includes('dev')}
        <div class="mb-12">
          <h3 class="font-display mb-2">Developer settings</h3>
          <section class="bg-gray-50 dark:bg-gray-800 rounded overflow-hidden p-4 grid grid-cols-2 gap-4">
            <label class="block">
              <p class="mb-2 text-sm">GitHub username</p>
              <input type="text" class="input input-secondary w-full" bind:value={form.github}>
            </label>
            <label class="block">
              <p class="mb-2 text-sm">PayPal link</p>
              <input type="text" class="input input-secondary w-full" bind:value={form.paypal}>
            </label>
            <label class="block">
              <p class="mb-2 text-sm">Patreon link</p>
              <input type="text" class="input input-secondary w-full" bind:value={form.patreon}>
            </label>
            <label class="block">
              <p class="mb-2 text-sm">Discord server code</p>
              <input type="text" class="input input-secondary w-full" placeholder="Example: ZHthyCw" bind:value={form.support}>
            </label>
          </section>
        </div>
      {/if}
      <footer class="flex justify-between">
        <button class="btn btn-primary bg-red-500 hover:bg-red-400 ring-red-500 ring-opacity-40 focus:bg-red-400" on:click={() => deleteModal = true}>
          <Icon src={Trash} class="w-5 h-5 mr-2" /> Delete account
        </button>
        <button class="btn btn-primary bg-green-500 hover:bg-green-400 ring-green-500 ring-opacity-40 focus:bg-green-400" on:click={submit}>
          {#if submitting}
            <Spinner class="mr-2" />
          {/if}
          Save changes
        </button>
      </footer>
    </main>
  </div>
</Layout>

<AreYouSure visible={deleteModal} title="Delete Account" on:close={() => deleteModal = false} on:confirm={destroy}>
  <p>Are you sure? Any data related to this account will be removed. This action <u>cannot</u> be undone.</p>
</AreYouSure>