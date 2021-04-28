<script>
  import {Inertia} from '@inertiajs/inertia';
  import {debounce} from 'debounce';
  import Portal from 'svelte-portal';
  import {fade, fly} from 'svelte/transition';

  import AdminLayout from '$layout/Admin.svelte';
  import Spinner from '$components/Spinner.svelte';

  // State
  export let users;
  export let query;

  const search = debounce(() => {
    Inertia.get('/admin/users', {
      search: query
    }, {
      replace: true
    })
  }, 500)

  let selectedUser;
  let userModal = false;
  const openUser = user => {
    selectedUser = {
      ...user,
      isDev: user.roles.some(el => el.name === 'dev')
    };
    userModal = !userModal;
  }

  let submitting = false;
  const updateUser = () => {
    Inertia.put('/admin/user', selectedUser, {
      preserveScroll: true,
      onStart() {
        submitting = true;
      },
      onFinish() {
        submitting = false;
      },
      onSuccess() {
        userModal = false;
      }
    })
  }
</script>

<AdminLayout title="Admin">
  <div class="mb-4">
    <input type="text" class="input" bind:value={query} placeholder="Search user..." on:keyup={search}>
  </div>
  <div class="bg-gray-50 dark:bg-gray-800 rounded overflow-hidden">
    <header class="grid grid-cols-[48px,1fr,1fr,.2fr] gap-4 p-4 items-center border-b border-gray-200 dark:border-gray-700">
      <span></span>
      <span class="text-xs text-gray-500 dark:text-gray-400">Username</span>
      <span class="text-xs text-gray-500 dark:text-gray-400">Slug</span>
      <span class="text-xs text-gray-500 dark:text-gray-400">ID</span>
    </header>
    {#each users as user}
      <button class="grid grid-cols-[48px,1fr,1fr,.2fr] gap-4 p-4 w-full text-left items-center border-b border-gray-200 dark:border-gray-700 hover:bg-white dark:hover:bg-gray-700 last:border-0 focus:outline-none" on:click={() => openUser(user)}>
        <img src={user.avatar} alt="User avatar" class="w-12 h-12 rounded-full">
        <span class="truncate">{user.name}</span>
        <span class="truncate">{user.slug}</span>
        <span class="truncate">{user.id}</span>
      </button>
    {/each}
  </div>
</AdminLayout>

{#if userModal}
  <Portal target="#layers">
    <div class="bg-black bg-opacity-60 fixed inset-0 z-10" transition:fade={{duration: 200}} on:click={() => userModal = false}></div>
    <form
      on:submit|preventDefault={updateUser}
      class="bg-gray-200 dark:bg-gray-900 fixed top-[5vh] left-1/2 transform -translate-x-1/2 rounded shadow-xl overflow-hidden z-20 w-[500px]"
      in:fly={{y: 15, duration: 200}}
      out:fly={{y: -15, duration: 200}}
    >
      <header class="p-4">
        <h3 class="text-lg text-gray-800 dark:text-gray-100 font-display">Editing {selectedUser.slug}</h3>
      </header>
      <div class="px-4 max-h-[65vh] overflow-auto">
        <label class="block mb-4">
          <div class="flex justify-between items-center mb-1">
            <p class="text-sm">Banned reason</p>
          </div>
          <textarea type="text" class="input w-full" bind:value={selectedUser.banned} />
        </label>
        <div class="flex mb-4">
          <label class="block cursor-pointer">
            <input type="checkbox"
              class="text-teal-500 border-0 w-6 h-6 rounded focus:ring-2 focus:ring-teal-500 focus:ring-opacity-40 focus:ring-offset-0"
              bind:checked={selectedUser.isDev}
            >
            <span class="ml-2 select-none">Developer</span>
          </label>
        </div>
      </div>
      <footer class="border-t border-gray-300 dark:border-gray-800 p-4 flex justify-end">
        <button type="button" class="btn btn-secondary mr-4" on:click={() => userModal = false}>
          Close
        </button>
        <button
          type="submit"
          class="btn btn-primary bg-green-500 hover:bg-green-400 focus:bg-green-400 focus:ring-green-500 focus:ring-opacity-30"
          class:pointer-events-none={submitting}
        >
          {#if submitting}
            <Spinner class="mr-2" />
          {/if}
          Save changes
        </button>
      </footer>
    </form>
  </Portal>
{/if}