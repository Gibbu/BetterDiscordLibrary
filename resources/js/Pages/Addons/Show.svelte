<script>
  import axios from 'axios';
  import tooltip from '$lib/tooltip';
  import {Inertia} from '@inertiajs/inertia';
  import {InertiaLink} from '@inertiajs/inertia-svelte';
  import Icon, {Download, Heart, Pencil, Eye, Refresh, Trash, QuestionMarkCircle} from 'svelte-hero-icons';
  import HRN from 'human-readable-numbers';
  import dayjs from 'dayjs';

  import Layout from '$layout/Layout.svelte';
  import Form from '$components/Addons/Form.svelte';
  import Gallery from '$components/Addons/Gallery.svelte';
  import Markdown from '$components/Markdown.svelte';
  import AreYouSure from '$components/Modals/AreYouSure.svelte';

  // State
  export let auth;
  export let addon;
  export let isLiked;

  let likeCD = false;
  const like = async() => {
    if (!likeCD) {
      likeCD = true;
      await axios.put(`/like/${addon.id}`);
      isLiked = !isLiked;
      setTimeout(() => {
        likeCD = false
      }, 1000)
    }
  }
  const download = () => {
    axios.put(`/download/${addon.id}`);
  }

  let addonEditModal = false;

  let deleteModal = false;
  const destroy = () => {
    Inertia.delete(`/${addon.type}s/${addon.name}`);
  }
</script>

<Layout title={addon.name}>
  <header class="mt-12 flex items-start justify-between">
    <div class="flex-1 mr-8">
      <h1 class="text-gray-800 dark:text-white font-display text-4xl capitalize">{addon.name}</h1>
      <Markdown string={addon.description} inline={true} />
    </div>
    <div class="flex">
      {#if auth}
        <button class="btn {isLiked ? 'btn-primary bg-pink-500 hover:bg-pink-400 focus:ring-pink-500 focus:ring-opacity-40' : 'btn-secondary'} mr-2" on:click={like}>
          <Icon src={Heart} solid={isLiked} class="w-5 h-5 mr-2" /> {isLiked ? 'Liked' : 'Like'}
        </button>
      {:else}
        <a href="/login" class="btn btn-secondary mr-2" use:tooltip={{content: 'You need to be logged in to like'}}>
          <Icon src={Heart} solid={isLiked} class="w-5 h-5 mr-2" /> Like
        </a>
      {/if}
      <a href={addon.download} target="_blank" class="btn btn-primary bg-teal-500 hover:bg-teal-400 focus:bg-teal-400 focus:ring-teal-500 focus:ring-opacity-40" on:click={download}>
        <Icon src={Download} class="w-5 h-5 mr-2" /> Download
      </a>
    </div>
  </header>
  <div class="mt-12 mb-52 grid grid-cols-[1fr,360px] gap-8 items-start">
    <main>
      <Gallery images={addon.images} />
      <p class="text-gray-500 dark:text-gray-400 text-sm my-12 select-none">Comments are coming soon.</p>
    </main>
    <aside>
      <div class="rounded bg-gray-50 dark:bg-gray-800 p-4">
        <section class="mb-6">
          <h3 class="text-gray-800 dark:text-gray-100 text-lg font-display mb-2">Developer</h3>
          <div class="flex items-center rounded-full overflow-auto group">
            <div class="flex items-center flex-1">
              <InertiaLink href="/users/{addon.user.name}/{addon.user.discrim}" class="flex items-center overflow-auto">
                <img src={addon.user.avatar} alt="Developer avatar" class="w-10 h-10 rounded-full">
              </InertiaLink>
              <InertiaLink href="/users/{addon.user.name}/{addon.user.discrim}" class="ml-3 text-lg hover:text-teal-500">
                {addon.user.name}
              </InertiaLink>
            </div>
            {#if addon.user.links.paypal}
              <a href={addon.user.links.paypal} use:tooltip={{content: 'PayPal', delay: [250, 0]}}>
                <svg class="w-5 h-5 hover:text-gray-800 dark:hover:text-white" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M111.4 295.9c-3.5 19.2-17.4 108.7-21.5 134-.3 1.8-1 2.5-3 2.5H12.3c-7.6 0-13.1-6.6-12.1-13.9L58.8 46.6c1.5-9.6 10.1-16.9 20-16.9 152.3 0 165.1-3.7 204 11.4 60.1 23.3 65.6 79.5 44 140.3-21.5 62.6-72.5 89.5-140.1 90.3-43.4.7-69.5-7-75.3 24.2zM357.1 152c-1.8-1.3-2.5-1.8-3 1.3-2 11.4-5.1 22.5-8.8 33.6-39.9 113.8-150.5 103.9-204.5 103.9-6.1 0-10.1 3.3-10.9 9.4-22.6 140.4-27.1 169.7-27.1 169.7-1 7.1 3.5 12.9 10.6 12.9h63.5c8.6 0 15.7-6.3 17.4-14.9.7-5.4-1.1 6.1 14.4-91.3 4.6-22 14.3-19.7 29.3-19.7 71 0 126.4-28.8 142.9-112.3 6.5-34.8 4.6-71.4-23.8-92.6z"></path></svg></a>
            {/if}
            {#if addon.user.links.patreon}
              <a href={addon.user.links.patreon} use:tooltip={{content: 'Patreon', delay: [250, 0]}}>
                <svg class="w-5 h-5 ml-3 hover:text-gray-800 dark:hover:text-white" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M512 194.8c0 101.3-82.4 183.8-183.8 183.8-101.7 0-184.4-82.4-184.4-183.8 0-101.6 82.7-184.3 184.4-184.3C429.6 10.5 512 93.2 512 194.8zM0 501.5h90v-491H0v491z"></path></svg></a>
            {/if}
          </div>
        </section>
        {#if addon.contributors.length > 0}
          <section class="mb-6">
            <h3 class="text-gray-800 dark:text-gray-100 text-lg font-display mb-2">Contributors</h3>
            {#each addon.contributors as contributor}
              <a href="https://github.com/{contributor}" target="_blank" class="flex items-center rounded-full overflow-auto group">
                <img src="https://github.com/{contributor}.png" alt="Contributor avatar" class="w-10 h-10 rounded-full">
                <span class="ml-3 text-lg group-hover:text-teal-500">
                  {contributor}
                </span>
              </a>
            {/each}
          </section>
        {/if}
        <section>
          <h3 class="text-gray-800 dark:text-gray-100 text-lg font-display mb-2">About</h3>
          <div class="flex items-center mb-1">
            <Icon src={Eye} class="w-5 h-5 mr-2" /> Views: {HRN.toHumanString(addon.view_count)}
          </div>
          <div class="flex items-center mb-1">
            <Icon src={Download} class="w-5 h-5 mr-2" /> Downloads: {HRN.toHumanString(addon.download_count)}
          </div>
          <div class="flex items-center mb-1">
            <Icon src={Heart} class="w-5 h-5 mr-2" /> Likes: {HRN.toHumanString(addon.likes_count)}
          </div>
          <div class="flex items-center">
            <Icon src={Refresh} class="w-5 h-5 mr-2" /> Page updated: {dayjs(addon.updated_at).format('D/M/YYYY - h:ma')}
          </div>
        </section>
      </div>
      {#if addon.user.links.support}
        <div class="rounded bg-gray-50 dark:bg-gray-800 p-4 mt-4">
          <section>
            <h3 class="text-gray-800 dark:text-gray-100 text-lg font-display mb-2">Need help?</h3>
            <a href={addon.user.links.support} class="btn btn-primary bg-teal-500 hover:bg-teal-400 focus:bg-teal-400 focus:ring-teal-500 focus:ring-opacity-40" target="_blank">
              <Icon src={QuestionMarkCircle} class="w-5 h-5 mr-2" />
              Join {addon.user.name}s server
            </a>
          </section>
        </div>
      {/if}
      {#if auth && auth.roles.includes('dev') && auth.id === addon.user.id}
        <div class="rounded bg-gray-50 dark:bg-gray-800 p-4 mt-4">
          <section>
            <h3 class="text-gray-800 dark:text-gray-100 text-lg font-display mb-2">Manage</h3>
            <button
              class="btn btn-tertiary w-full"
              on:click={() => addonEditModal = true}
            >
              <Icon src={Pencil} class="w-5 h-5 mr-2" /> Edit
            </button>
            <button
              class="btn w-full mt-2 border-2 rounded border-gray-200 dark:border-gray-700 hover:text-red-500 hover:border-red-500 dark:hover:border-red-500 focus:border-red-500 dark:focus:border-red-500 focus:text-red-500"
              on:click={() => deleteModal = true}
            >
              <Icon src={Trash} class="w-5 h-5 mr-2" /> Delete
            </button>
          </section>
        </div>
      {/if}
    </aside>
  </div>
</Layout>

<Form visible={addonEditModal} data={addon} editing={true} on:close={() => addonEditModal = false} />

<AreYouSure visible={deleteModal} title="Delete {addon.name}" on:close={() => deleteModal = false} on:confirm={destroy}>
  <p>Are you sure? This will remove {addon.name} and anything related to it from the database.</p>
</AreYouSure>