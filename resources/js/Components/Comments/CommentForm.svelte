<script>
  import {Inertia} from '@inertiajs/inertia';
  import {page} from '@inertiajs/inertia-svelte';
  import {createEventDispatcher} from 'svelte';
  const dispatch = createEventDispatcher();

  import Spinner from '../Spinner.svelte';

  // State
  export let form;
  export let method;
  export let url;
  export let isReply = false;
  export let isEditing = false;
  let {auth} = $page.props;


  let input;
  let errors;
  let submitting = false;
  const submit = () => {
    Inertia.visit(url, {
      method,
      data: form,
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
        form.message = '';
        close();
      }
    })
  }

  $: if (input) input.focus();
  
  const close = () => {
    dispatch('close');
  }
</script>

<form class="flex items-start w-full" on:submit|preventDefault={submit}>
  {#if !isEditing}
    <img src={auth.avatar} alt="User avatar" class="{isReply ? 'w-8 h-8' : 'w-10 h-10'} rounded-full mr-4">
  {/if}
  <div class="flex-1">
    <textarea bind:this={input} class="input w-full {errors?.message ? '!border-red-500 hover:!border-red-400' : ''}" placeholder="Your reply..." bind:value={form.message} />
    <footer class="flex justify-between mt-2">
      {#if errors?.message}
        <small class="text-red-500">{errors.message}</small>
      {:else}
        <small></small>
      {/if}
      <div class="flex">
        <button type="button" class="btn btn-small mr-2 btn-secondary" on:click={close}>Close</button>
        <button
          type="submit"
          class="btn btn-small btn-primary bg-green-500 hover:bg-green-400 focus:bg-green-400 focus:ring-green-500 focus:ring-opacity-40"
          class:pointer-events-none={submitting}
        >
          {#if submitting}
            <Spinner class="mr-2" />
          {/if}
          {isEditing ? 'Save changes' : 'Post comment'}
        </button>
      </div>
    </footer>
  </div>
</form>