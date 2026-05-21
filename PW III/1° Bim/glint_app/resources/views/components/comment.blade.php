<div class="bg-gray-50 p-3 rounded-lg">
  <div class="flex items-center justify-between mb-2">
    <div class="flex flex-1 space-x-1 items-center">
      <div class="w-6 h-6 rounded-full bg-linear-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white text-sm shrink-0">
        {{ $comment->user->username[0] }}
      </div>
      <p class="text-sm font-semibold text-gray-800">{{ $comment->user->username }}</p>
      <p class="text-xs text-gray-500">{{ $comment->created_at->format('d/m/y H:i') }}</p>
    </div>
    @if(auth()->check() && auth()->user()->id === $comment->user_id)
    <div class="flex items-center space-x-2">
      <button type="button" class="text-xs text-red-600 hover:text-red-800 font-semibold js-open-modal" data-modal-target="deleteCommentModal-{{ $comment->id }}">Excluir</button>
    </div>
    @endif
  </div>
  <p class="text-sm text-gray-700">{{ $comment->body }}</p>
  @if(auth()->check() && auth()->user()->id === $comment->user_id)
  <form id="delete-comment-form-{{ $comment->id }}" method="POST" action="{{ route('comments.delete', $comment) }}" style="display: none;">
    @csrf @method('DELETE')
  </form>
  @endif
</div>

@if(auth()->check() && auth()->user()->id === $comment->user_id)
<x-modal id="deleteCommentModal-{{ $comment->id }}" title="Confirmar Exclusão">
  <div class="text-center py-4">
    <div class="mb-4">
      <i class="bi bi-exclamation-triangle text-4xl text-red-600"></i>
    </div>
    <p class="text-gray-700 mb-6">Tem certeza que deseja excluir este comentário?</p>
    <div class="flex justify-center space-x-4">
      <button type="button" class="js-close-modal px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold">Cancelar</button>
      <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold" onclick="document.getElementById('delete-comment-form-{{ $comment->id }}').submit();">
        Confirmar Exclusão
      </button>
    </div>
  </div>
</x-modal>
@endif