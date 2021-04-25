<script>
  import {Inertia} from '@inertiajs/inertia';
  import Icon, {Sun, Moon, Trash} from 'svelte-hero-icons';

  import Layout from '$layout/Layout.svelte';
  import Spinner from '$components/Spinner.svelte';
  import AreYouSure from '$components/Modals/AreYouSure.svelte';

  // State
  export let auth;

  let theme = localStorage.theme;

  const setTheme = value => {
    theme = value;
    localStorage.theme = value;
    document.documentElement.setAttribute('class', value);
  }

  let form = {
    github: auth.links?.github || '',
    paypal: auth.links?.paypal || '',
    patreon: auth.links?.patreon || '',
    support: auth.links?.support || ''
  };

  let submitting = false;
  const submit = () => {
    Inertia.put('/user', form, {
      preserveScroll: true,
      onStart() {
        submitting = true;
      },
      onFinish() {
        submitting = false;
      }
    })
  }

  let deleteModal = false;
  const destroy = () => {
    Inertia.delete('/user');
  }
</script>

<Layout title="Profile settings">
  <main class="my-12">
    <div class="mb-12">
      <h3 class="font-display mb-2">Appearance</h3>
      <section class="bg-gray-50 dark:bg-gray-800 rounded overflow-hidden p-4">
        <button
          class="flex items-center mb-4 w-full py-4 px-5 rounded border-2 focus:outline-none {theme === 'light' ? 'text-white bg-teal-500 border-teal-500' : 'border-gray-200 hover:border-gray-300 dark:border-gray-700 dark:hover:border-gray-600'}"
          on:click={() => setTheme('light')}
        >
          <Icon src={Sun} class="w-5 h-5 mr-3" /> Light Theme
        </button>
        <button
          class="flex items-center w-full py-4 px-5 rounded border-2 focus:outline-none {theme === 'dark' ? 'text-white bg-teal-500 border-teal-500' : 'border-gray-200 hover:border-gray-300 dark:border-gray-700 dark:hover:border-gray-600'}"
          on:click={() => setTheme('dark')}
        >
          <Icon src={Moon} class="w-5 h-5 mr-3" /> Dark Theme
        </button>
      </section>
    </div>
    {#if auth.roles.includes('dev')}
      <div class="mb-12">
        <h3 class="font-display mb-2">Developer settings</h3>
        <section class="bg-gray-50 dark:bg-gray-800 rounded overflow-hidden p-4 grid grid-cols-2 gap-4">
          <label class="text-sm mb-1">
            <p class="mb-2">GitHub username</p>
            <input type="text" class="input input-secondary w-full" bind:value={form.github}>
          </label>
          <label class="text-sm mb-1">
            <p class="mb-2">PayPal link</p>
            <input type="text" class="input input-secondary w-full" bind:value={form.paypal}>
          </label>
          <label class="text-sm mb-1">
            <p class="mb-2">Patreon link</p>
            <input type="text" class="input input-secondary w-full" bind:value={form.patreon}>
          </label>
          <label class="text-sm mb-1">
            <p class="mb-2">Discord server code</p>
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
</Layout>

<AreYouSure visible={deleteModal} title="Delete Account" on:close={() => deleteModal = false} on:confirm={destroy}>
  <p>Are you sure? Any data related to this account will be removed. This action <u>cannot</u> be undone.</p>
</AreYouSure>