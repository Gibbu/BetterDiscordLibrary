<script>
  import {Inertia} from '@inertiajs/inertia';

  import AdminLayout from '$layout/Admin.svelte';
  import Spinner from '$components/Spinner.svelte';

  // State
  export let announcement;

  let form = {
    title: announcement?.title || '',
    message: announcement?.message || '',
  }

  let errors;
  let submitting = false;
  const submit = () => {
    Inertia.visit(`/admin/announcement${announcement ? `/${announcement.id}` : ''}`, {
      method: announcement ? 'PUT' : 'POST',
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
        errors = null;
      }
    })
  }
  
  const destroy = () => {
    Inertia.delete(`/admin/announcement/${announcement.id}`, {
      onSuccess() {
        form.title = '';
        form.message = '';
      }
    });
  }
</script>

<AdminLayout title="Admin">
  <form on:submit|preventDefault={submit}>
    <div class="bg-gray-500 dark:bg-gray-800 rounded p-4">
      <div class="mb-4">
        <p class="text-sm mb-2">Title</p>
        <input type="text" class="input input-secondary w-full {errors?.title ? '!border-red-500 hover:!border-red-400' : ''}" bind:value={form.title}>
        {#if errors?.title}
          <small class="text-red-500">{errors.title}</small>
        {/if}
      </div>
      <div>
        <p class="text-sm mb-2">Message</p>
        <textarea type="text" class="input input-secondary w-full {errors?.message ? '!border-red-500 hover:!border-red-400' : ''}" bind:value={form.message} />
        {#if errors?.message}
          <small class="text-red-500">{errors.message}</small>
        {/if}
      </div>
    </div>
    <footer class="flex justify-end mt-4">
      {#if announcement}
        <button
          type="button"
          class="btn btn-text mr-4 text-red-500 hover:bg-red-500 hover:bg-opacity-5 focus:bg-red-500 focus:bg-opacity-10"
          on:click={destroy}
        >
          Delete
        </button>
      {/if}
      <button
        type="submit"
        class="btn btn-primary bg-green-500 hover:bg-green-400 focus:bg-green-500 focus:ring-green-500 focus:ring-opacity-40"
        class:pointer-events-none={submitting}
      >
        {#if submitting}
          <Spinner class="mr-2" />
        {/if}
        {announcement ? 'Save changes' : 'Submit'}
      </button>
    </footer>
  </form>
</AdminLayout>