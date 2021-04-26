<script>
  import {Inertia} from '@inertiajs/inertia';
  import {createEventDispatcher} from 'svelte';
  import {fade, fly} from 'svelte/transition';
  import Icon, {Trash, Plus, InformationCircle} from 'svelte-hero-icons';
  import tooltip from '$lib/tooltip';
  import Portal from 'svelte-portal';
  const dispatch = createEventDispatcher();

  import Spinner from '../Spinner.svelte';

  export let data = {
    id: null,
    type: '',
    name: '',
    description: '',
    download: '',
    thumbnail: '',
    images: [''],
    contributors: [''],
    release: '',
    source: ''
  }
  export let editing = false;
  export let visible = false;

  let submitting = false;
  let errors;
  const submit = () => {
    Inertia.visit((editing ? `/${data.type}s/${data.name}` : '/addon'), {
      method: (editing ? 'put' : 'post'),
      data,
      preserveState: false,
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
        close();
      }
    });
  }
  const close = () => {
    dispatch('close');
  }

  const removeRow = (property, index) => {
    data[property] = data[property].filter((el, i) => i !== index);
  }
  const addRow = (property) => {
    data[property] = [...data[property], ''];
  }
</script>

{#if visible}
  <Portal target="#layers">
    <div class="bg-black bg-opacity-60 fixed inset-0 z-10" transition:fade={{duration: 200}} on:click={close}></div>
    <form
      on:submit|preventDefault={submit}
      class="bg-gray-200 dark:bg-gray-900 fixed top-[5vh] left-1/2 transform -translate-x-1/2 rounded shadow-xl overflow-hidden z-20 w-[500px]"
      in:fly={{y: 15, duration: 200}}
      out:fly={{y: -15, duration: 200}}
    >
      <header class="p-4">
        <h3 class="text-lg text-gray-800 dark:text-gray-100 font-display">{editing ? `Editing ${data.name}` : 'Submit an Addon'}</h3>
      </header>
      <div class="px-4 max-h-[65vh] overflow-auto">
        {#if !editing}
          <label class="block mb-4">
            <p class="text-sm mb-1">Type <span class="text-red-500">*</span></p>
            <select bind:value={data.type} class="input w-full {errors?.type ? '!border-red-500 hover:!border-red-400' : ''}">
              <option value="" hidden selected={!editing}>Select a type</option>
              <option class="bg-white dark:bg-gray-800" value="theme" selected={editing && data.type === 'theme'}>Theme</option>
              <option class="bg-white dark:bg-gray-800" value="plugin" selected={editing && data.type === 'plugin'}>Plugin</option>
            </select>
            {#if errors?.type}
              <small class="text-red-500">{errors.type}</small>
            {/if}
          </label>
          <label class="block mb-4">
            <p class="text-sm mb-1">Name <span class="text-red-500">*</span></p>
            <input type="text" class="input w-full {errors?.name ? '!border-red-500 hover:!border-red-400' : ''}" bind:value={data.name}>
            {#if errors?.name}
              <small class="text-red-500">{errors.name}</small>
            {/if}
          </label>
        {/if}
        <label class="block mb-4">
          <div class="flex justify-between items-center mb-1">
            <p class="text-sm">Description <span class="text-red-500">*</span></p>
            <small class="text-xs text-gray-400 dark:text-gray-500">(inline markdown supported)</small>
          </div>
          <textarea type="text" class="input w-full {errors?.description ? '!border-red-500 hover:!border-red-400' : ''}" bind:value={data.description} />
          {#if errors?.description}
            <small class="text-red-500">{errors.description}</small>
          {/if}
        </label>
        <label class="block mb-4">
          <p class="text-sm mb-1">Download link <span class="text-red-500">*</span></p>
          <input type="text" class="input w-full mr-2 {errors?.download ? '!border-red-500 hover:!border-red-400' : ''}" bind:value={data.download} placeholder="Download URL...">
          {#if errors?.download}
          <small class="text-red-500">{errors.download}</small>
          {/if}
        </label>
        <div class="mb-4">
          <p class="text-sm mb-1 flex items-center">
            Images
            <span class="ml-2" use:tooltip={{content: 'The first image will be used for the card thumbnail. Images are in a 16:9 aspect ratio.'}}>
              <Icon src={InformationCircle} class="w-4 h-4" />
            </span>
          </p>
          {#each data.images as img, i}
            <div class="flex mb-1">
              <input type="text" class="input w-full mr-2" bind:value={img} placeholder="Image URL...">
              {#if i === 0}
                <button type="button" class="btn text-teal-500 hover:bg-teal-500 hover:bg-opacity-5 focus:bg-teal-500 focus:bg-opacity-10" on:click={() => addRow('images')}>
                  <Icon src={Plus} class="w-5 h-5" />
                </button>
              {:else}
                <button type="button" class="btn text-red-500 hover:bg-red-500 hover:bg-opacity-5 focus:bg-red-500 focus:bg-opacity-10" on:click={() => removeRow('images', i)}>
                  <Icon src={Trash} class="w-5 h-5" />
                </button>
              {/if}
            </div>
          {/each}
        </div>
        <div class="mb-4">
          <p class="text-sm mb-1">Contributors</p>
          <div class="flex mb-1">
            <input type="text" class="input w-full mr-2" bind:value={data.contributors[0]} placeholder="GitHub username">
            <button type="button" class="btn text-teal-500 hover:bg-teal-500 hover:bg-opacity-5 focus:bg-teal-500 focus:bg-opacity-10" on:click={() => addRow('contributors')}>
              <Icon src={Plus} class="w-5 h-5" />
            </button>
          </div>
          {#each data.contributors as contributor, i}
            {#if i !== 0}
              <div class="flex mb-1">
                <input type="text" class="input w-full mr-2" bind:value={contributor} placeholder="GitHub username">
                <button type="button" class="btn text-red-500 hover:bg-red-500 hover:bg-opacity-5 focus:bg-red-500 focus:bg-opacity-10" on:click={() => removeRow('contributors', i)}>
                  <Icon src={Trash} class="w-5 h-5" />
                </button>
              </div>
            {/if}
          {/each}
        </div>
        <div class="flex mb-4">
          <label class="block mr-4 flex-1">
            <div class="flex items-center justify-between">
              <p class="text-sm mb-1">Release date <span class="text-red-500">*</span></p>
              <span use:tooltip={{content: 'When the addon was posted the the official theme/plugin channels'}}>
                <Icon src={InformationCircle} class="w-4 h-4" />
              </span>
            </div>
            <input type="date" class="input w-full {errors?.release ? '!border-red-500 hover:!border-red-400' : ''}" bind:value={data.release}>
            {#if errors?.release}
              <small class="text-red-500">{errors.release}</small>
            {/if}
          </label>
          <label class="block flex-1">
            <p class="text-sm mb-1">Source link <span class="text-red-500">*</span></p>
            <input type="text" class="input w-full {errors?.source ? '!border-red-500 hover:!border-red-400' : ''}" bind:value={data.source}>
            {#if errors?.source}
              <small class="text-red-500">{errors.source}</small>
            {/if}
          </label>
        </div>
      </div>
      <footer class="border-t border-gray-300 dark:border-gray-800 p-4 flex justify-end">
        <button type="button" class="btn btn-secondary mr-4" on:click={close}>
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
          {editing ? 'Save changes' : 'Submit addon'}
        </button>
      </footer>
    </form>
  </Portal>
{/if}