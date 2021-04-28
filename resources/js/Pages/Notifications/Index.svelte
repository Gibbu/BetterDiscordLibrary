<script>
  import Icon, {Eye} from 'svelte-hero-icons';
  import {Inertia} from '@inertiajs/inertia';
  import tooltip from '$lib/tooltip';
  import {InertiaLink} from '@inertiajs/inertia-svelte';
  import dayjs from 'dayjs';
  import relTime from 'dayjs/plugin/relativeTime';

  dayjs.extend(relTime);

  import Layout from '$layout/Layout.svelte';

  // State
  export let notifications;
  $: unreadCount = notifications.filter(el => el.read_at === null).length;

  console.log(notifications);
</script>

<Layout title="Notifications">
  <header class="bg-gray-100 dark:bg-gray-950 border-gray-300 dark:border-gray-800 py-12 border-b flex items-center">
    <div class="wrap flex items-center justify-between">
      <h1 class="text-gray-800 dark:text-white text-4xl font-display capitalize">Notifications</h1>
      {#if unreadCount > 0}
        <InertiaLink method="PUT" href="/notifications/readAll" class="btn btn-small btn-secondary">
          <Icon src={Eye} class="w-4 h-4 mr-2" /> Mark all as read
        </InertiaLink>
      {/if}
    </div>
  </header>

  <div class="wrap mt-12">
    {#if notifications.length > 0}
      <div class="bg-gray-50 dark:bg-gray-800 rounded overflow-hidden">
        {#each notifications as notification}
          <div class="relative p-4 border-b flex justify-between items-center border-gray-200 dark:border-gray-700 hover:bg-white dark:hover:bg-gray-700 last:border-0">
            {#if notification.read_at === null}
              <div class="absolute left-0 h-full w-1 bg-teal-500"></div>
            {/if}
            <InertiaLink href="/{notification.addon.type}s/{notification.addon.name}" class="absolute inset-0"></InertiaLink>
            <div class="flex items-center pointer-events-none">
              <img src={notification.user.avatar} alt="User avatar" class="w-12 h-12 rounded-full">
              <div class="ml-4">
                <p class="select-none">{notification.user.name} {notification.message} {notification.addon.name}</p>
                <time datetime={notification.created_at} class="block text-sm opacity-60 select-none">{dayjs(notification.created_at).fromNow()}</time>
              </div>
            </div>
            {#if notification.read_at === null}
              <span use:tooltip={{content: 'Mark as read'}}>
                <InertiaLink
                  href="/notifications/read/{notification.id}"
                  method="PUT"
                  class="block relative p-2 z-10 hover:text-teal-600 dark:hover:text-teal-500"
                >
                  <Icon src={Eye} class="w-5 h-5" />
                </InertiaLink>
              </span>
            {/if}
          </div>
        {/each}
      </div>
    {:else}
      <p class="text-sm opacity-60 select-none">You have no notifications at this time</p>
    {/if}
  </div>
</Layout>