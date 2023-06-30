<div class="document">
    <div class="document__ico" data-toggle="tooltip" data-html="true" title="{{ $document->description }}">
        @if ($download)
            @if($lightbox)
                <i class="far fa-file-image fa-2x"></i>
            @else
                <i class="far fa-file-alt fa-2x"></i>
            @endif
        @else
            <i class="far fa-file-pdf fa-2x"></i>
        @endif
    </div>
    <div class="document__info">
        <p class="document__title">{{ $document->title }}</p>
        @if ($download)
            <a class="document__link" target="_blank" href="{{ $document->show_url }}" download="">
                <i class="fas fa-file-download"></i>
                Скачать
            </a>
            (Размер {{ $size/1000 }} Кб)
            @if($lightbox)
                <a class="document__link ml-sm-2" href="{{ $document->show_url }}" data-lightbox="{{ $document->slug }}">
                    <i class="far fa-eye"></i> Просмотр
                </a>
            @else
                <a class="document__link ml-sm-2" target="_blank" href="http://docs.google.com/gview?url={{ $document->show_url }}&embedded=true">
                    <i class="far fa-eye"></i> Просмотр
                </a>
            @endif
        @else()
            (Размер {{ $size/1000 }} Кб)
            <a class="document__link ml-sm-2" target="_blank" href="{{ $document->show_url }}">
                <i class="far fa-eye"></i> Просмотр
            </a>
        @endif
        @foreach($signatures as $signature)
            <div class="document-signature__link ml-sm-2 rounded-pill"
                 data-toggle="modal"
                 data-target="#Document{{ $signature->id }}SignatureModal">
                <i class="fas fa-signature" title="ЭП"></i>
            </div>
            <!-- Modal -->
            <div class="modal fade document-signature__modal" id="Document{{ $signature->id }}SignatureModal" tabindex="-1"
                 aria-labelledby="Document{{ $signature->id }}SignatureModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="Document{{ $signature->id }}SignatureModalLabel">
                                Подписано квалифицированной
                                электронной подписью
                            </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                           <div class="row">
                               @isset($signature->person )
                                   <div class="col-12 col-sm-4 document-signature__header">
                                       Подписант:
                                   </div>
                                   <div class="col-12 col-sm-8 mb-3">
                                       {{ $signature->person }}
                                   </div>
                               @endisset
                               @isset($signature->position )
                                   <div class="col-12 col-sm-4 document-signature__header">
                                       Должность:
                                   </div>
                                   <div class="col-12 col-sm-8 mb-3">
                                       {{ $signature->position }}
                                   </div>
                               @endisset
                               @isset($signature->organization )
                                   <div class="col-12 col-sm-4 document-signature__header">
                                       Организация:
                                   </div>
                                   <div class="col-12 col-sm-8 mb-3 document-signature__content">
                                       {{ $signature->organization }}
                                   </div>
                               @endisset
                               @isset($signature->date )
                                   <div class="col-12 col-sm-4 document-signature__header">
                                       Дата подписания:
                                   </div>
                                   <div class="col-12 col-sm-8 mb-3 document-signature__content">
                                       {{ $signature->date }}
                                   </div>
                               @endisset
                               @isset($signature->certificate )
                                   <div class="col-12 col-sm-4 document-signature__header">
                                       Сертификат:
                                   </div>
                                   <div class="col-12 col-sm-8 mb-3 document-signature__content">
                                       {{ $signature->certificate }}
                                   </div>
                               @endisset
                               @isset($signature->issued )
                                   <div class="col-12 col-sm-4 document-signature__header">
                                       Кем выдан:
                                   </div>
                                   <div class="col-12 col-sm-8 mb-3 document-signature__content">
                                       {{ $signature->issued }}
                                   </div>
                               @endisset
                               @isset($signature->period )
                                   <div class="col-12 col-sm-4 document-signature__header">
                                       Период действия сертификата:
                                   </div>
                                   <div class="col-12 col-sm-8 mb-3 document-signature__content">
                                       {{ $signature->period }}
                                   </div>
                               @endisset
                                   <div class="col-12 my-3">
                                       <a class="text-info document-signature__show-link" data-toggle="collapse"
                                          href="#collapseSignature{{ $signature->id }}"
                                          role="button" aria-expanded="false" a
                                          ria-controls="collapseExample">
                                           Показать/скрыть электронную подпись
                                       </a>
                                       <div class="collapse" id="collapseSignature{{ $signature->id }}">
                                           <div class="card card-body document-signature__show">
                                               {{  Storage::get($signature->path) }}
                                           </div>
                                       </div>
                                   </div>

                           </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>