<div class="document">
    <div class="document__ico" data-toggle="tooltip" data-html="true" title="{{ $document->description }}">
        <i class="fas fa-file-alt fa-2x"></i>
    </div>
    <div class="document__info">
        <p class="document__title">{{ $document->title }}</p>
        @if ($download)
            <a class="document__link" target="_blank" href="{{ $document->show_url }}" download="">
                <i class="fas fa-file-download"></i>
                Скачать
            </a>
            (Размер {{ $size/1000 }} Кб)
            <a class="document__link ml-sm-2" target="_blank" href="http://docs.google.com/gview?url={{ $document->show_url }}&embedded=true">
                <i class="far fa-eye"></i> Просмотр
            </a>

            @else()
            (Размер {{ $size/1000 }} Кб)
            <a class="document__link ml-sm-2" target="_blank" href="{{ $document->show_url }}">
                <i class="far fa-eye"></i> Просмотр
            </a>
        @endif
        @foreach($signatures as $signature)
            <div class="document__signature-link ml-sm-2 rounded-pill"
                 data-toggle="modal"
                 data-target="#Document{{ $signature->id }}SignatureModal">
                <i class="fas fa-signature" title="ЭП"></i>
            </div>
            <!-- Modal -->
            <div class="modal fade document__signature-modal" id="Document{{ $signature->id }}SignatureModal" tabindex="-1"
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
                                   <div class="col-12 col-sm-4">
                                       Подписант:
                                   </div>
                                   <div class="col-12 col-sm-8 font-weight-bolder mb-3">
                                       {{ $signature->person }}
                                   </div>
                               @endisset
                               @isset($signature->position )
                                   <div class="col-12 col-sm-4">
                                       Должность:
                                   </div>
                                   <div class="col-12 col-sm-8 font-weight-bolder mb-3">
                                       {{ $signature->position }}
                                   </div>
                               @endisset
                               @isset($signature->organization )
                                   <div class="col-12 col-sm-4">
                                       Организация:
                                   </div>
                                   <div class="col-12 col-sm-8 font-weight-bolder mb-3">
                                       {{ $signature->organization }}
                                   </div>
                               @endisset
                               @isset($signature->date )
                                   <div class="col-12 col-sm-4">
                                       Дата подписания:
                                   </div>
                                   <div class="col-12 col-sm-8 font-weight-bolder mb-3">
                                       {{ $signature->date }}
                                   </div>
                               @endisset
                               @isset($signature->certificate )
                                   <div class="col-12 col-sm-4">
                                       Сертификат:
                                   </div>
                                   <div class="col-12 col-sm-8 font-weight-bolder mb-3">
                                       {{ $signature->certificate }}
                                   </div>
                               @endisset
                               @isset($signature->issued )
                                   <div class="col-12 col-sm-4">
                                       Кем выдан:
                                   </div>
                                   <div class="col-12 col-sm-8 font-weight-bolder mb-3">
                                       {{ $signature->issued }}
                                   </div>
                               @endisset
                               @isset($signature->period )
                                   <div class="col-12 col-sm-4">
                                       Период действия сертификата:
                                   </div>
                                   <div class="col-12 col-sm-8 font-weight-bolder mb-3">
                                       {{ $signature->period }}
                                   </div>
                               @endisset
                                   <div class="col-12 my-3">
                                       <a class="text-info" data-toggle="collapse"
                                          href="#collapseSignature{{ $signature->id }}"
                                          role="button" aria-expanded="false" a
                                          ria-controls="collapseExample">
                                           Показать/скрыть электронную подпись
                                       </a>
                                       <div class="collapse" id="collapseSignature{{ $signature->id }}">
                                           <div class="card card-body">
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