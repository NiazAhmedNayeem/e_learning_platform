@extends('backend.layouts.master')
@section('title', 'Site Settings')

@section('main-content')
<div class="container-fluid py-4">
  <div class="row gx-4">
    <!-- LEFT SIDEBAR -->
    <aside class="col-lg-3 mb-4">
      <div class="card shadow border-0 rounded-3" style="background: #212529">
        <div class="card-body p-3 text-white">
          <div class="d-flex align-items-center mb-3 border-bottom">
            <div class="me-3 rounded-3 d-flex align-items-center justify-content-center" style="margin-top: -10px">
              <i class="fa-solid fa-gear" style="height: 45px; width: 45px;"></i>
            </div>
            <div class="mb-3 mt-2">
              <h5 class="mb-0 fw-bold">Site Settings</h5>
              <small class="text-muted">Manage site configuration</small>
            </div>
          </div>
          <nav class="nav nav-pills flex-column" id="settingsSidebar" role="tablist" aria-orientation="vertical">
            <a class="nav-link text-white active mb-1" data-bs-toggle="pill" href="#tab-general" role="tab"><i class="bi bi-house-gear me-2"></i>General</a>
            <a class="nav-link text-white mb-1" data-bs-toggle="pill" href="#tab-header" role="tab"><i class="bi bi-layout-text-window-reverse me-2"></i>Header</a>
            <a class="nav-link text-white mb-1" data-bs-toggle="pill" href="#tab-footer" role="tab"><i class="bi bi-window me-2"></i>Footer</a>
            <a class="nav-link text-white mb-1" data-bs-toggle="pill" href="#tab-privacy" role="tab"><i class="bi bi-shield-lock me-2"></i>Privacy</a>
            <a class="nav-link text-white mb-1" data-bs-toggle="pill" href="#tab-terms" role="tab"><i class="bi bi-file-earmark-text me-2"></i>Terms</a>
            <a class="nav-link text-white mb-1" data-bs-toggle="pill" href="#tab-seo" role="tab"><i class="bi bi-search me-2"></i>SEO</a>
            <a class="nav-link text-white mb-1" data-bs-toggle="pill" href="#tab-social" role="tab"><i class="bi bi-share me-2"></i>Social</a>
            <a class="nav-link text-white mb-1" data-bs-toggle="pill" href="#tab-smtp" role="tab"><i class="bi bi-envelope-paper me-2"></i>SMTP / Mail</a>
          </nav>
          <div class="mt-3 text-center">
            <small class="text-muted">Pro tip: Save each tab after editing</small>
          </div>
        </div>
      </div>
    </aside>

    <!-- RIGHT CONTENT -->
    <section class="col-lg-9">
      <div class="card shadow border-0 rounded-3" style="background: #212529">
        <div class="card-body p-0">
          <div class="d-flex justify-content-between align-items-center px-4 py-3 border-bottom text-white">
            <div>
              <h5 class="mb-0 fw-bold">Settings</h5>
              <small class="text-muted">Configure site-wide options</small>
            </div>
          </div>

          <div class="p-4">
            <div class="tab-content" id="settingsTabContent">

              <!-- GENERAL TAB -->
              <div class="tab-pane fade show active" id="tab-general" role="tabpanel">
                <div class="row g-3">
                  <div class="col-md-5">
                    <div class="card border-0 shadow h-100">
                      <div class="card-body">
                        <h6 class="fw-bold mb-3">Site Identity</h6>
                        <form class="ajaxForm" enctype="multipart/form-data">
                          @csrf
                          <div class="mb-3">
                            <label class="form-label small">Logo</label>
                            <div class="d-flex gap-3 align-items-center">
                              <div id="logoPreview" class="border rounded-3 d-flex align-items-center justify-content-center" style="width:120px;height:80px;background:#f8f9fa;">
                                @php $logo = $settings['site_logo'] ?? null; @endphp

                                <img id="logoImg" src="{{ $logo ? asset('public/upload/site/'.$logo) : '' }}" 
                                    style="max-width:100%;max-height:100%;{{ $logo ? '' : 'display:none;' }}">
                                <span id="logoText" class="text-muted small">{{ $logo ? '' : 'No logo' }}</span>
                                
                              </div>
                              <div class="flex-fill">
                                <input type="file" name="site_logo" class="form-control form-control-sm" accept="image/*" onchange="previewLogo(event)">
                                <small class="text-muted">Recommended: 300x100 px, PNG or SVG</small>
                              </div>
                            </div>
                          </div>
                          <div class="mb-3">
                            <label class="form-label small">Site Name</label>
                            <input name="site_name" value="{{ $settings['site_name'] ?? '' }}" type="text" class="form-control form-control-sm">
                          </div>
                          <div class="mb-3">
                            <label class="form-label small">Tagline</label>
                            <input name="site_tagline" value="{{ $settings['site_tagline'] ?? '' }}" type="text" class="form-control form-control-sm">
                          </div>
                          <div class="text-end">
                            <button type="submit" class="btn btn-sm btn-dark"><i class="bi bi-save me-1"></i>Save General</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-7">
                    <div class="card border-0 shadow h-100">
                      <div class="card-body">
                        <h6 class="fw-bold mb-3">Quick Info & Preview</h6>
                        <dl class="row">
                          <dt class="col-sm-4 small text-muted">Current Site Name</dt>
                          <dd class="col-sm-8 small"><strong>{{ $settings['site_name'] ?? '—' }}</strong></dd>
                          <dt class="col-sm-4 small text-muted">Tagline</dt>
                          <dd class="col-sm-8 small">{{ $settings['site_tagline'] ?? '—' }}</dd>
                          <dt class="col-sm-4 small text-muted">Logo</dt>
                          <dd class="col-sm-8 small">{{ empty($settings['site_logo']) ? 'No logo' : 'Uploaded' }}</dd>
                        </dl>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- HEADER TAB -->
              <div class="tab-pane fade" id="tab-header" role="tabpanel">
                <div class="card border-0 shadow">
                  <div class="card-body">
                    <h6 class="fw-bold mb-3">Header Settings</h6>
                    <form class="ajaxForm">
                      @csrf
                      <div class="row g-3">
                        <div class="col-md-6">
                          <label class="form-label small">Show Search</label>
                          <select name="header_search" class="form-select form-select-sm">
                            <option value="1" {{ (isset($settings['header_search']) && $settings['header_search']=='1') ? 'selected':'' }}>Yes</option>
                            <option value="0" {{ (isset($settings['header_search']) && $settings['header_search']=='0') ? 'selected':'' }}>No</option>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <label class="form-label small">Show Contact Link</label>
                          <select name="header_contact" class="form-select form-select-sm">
                            <option value="1" {{ (isset($settings['header_contact']) && $settings['header_contact']=='1') ? 'selected':'' }}>Yes</option>
                            <option value="0" {{ (isset($settings['header_contact']) && $settings['header_contact']=='0') ? 'selected':'' }}>No</option>
                          </select>
                        </div>
                      </div>
                      <div class="text-end mt-3">
                        <button type="submit" class="btn btn-sm btn-dark"><i class="bi bi-save me-1"></i>Save Header</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <!-- FOOTER TAB -->
              <div class="tab-pane fade" id="tab-footer" role="tabpanel">
                <div class="card border-0 shadow">
                  <div class="card-body">
                    <h6 class="fw-bold mb-3">Footer Settings</h6>
                    <form class="ajaxForm">
                      @csrf
                      <div class="mb-3">
                        <label class="form-label small">Footer Text</label>
                        <textarea name="footer_text" rows="3" class="form-control form-control-sm">{{ $settings['footer_text'] ?? '' }}</textarea>
                      </div>
                      <div class="mb-3">
                        <label class="form-label small">Footer Links (JSON)</label>
                        <textarea name="footer_links" rows="3" class="form-control form-control-sm">{{ $settings['footer_links'] ?? '' }}</textarea>
                      </div>
                      <div class="text-end mt-3">
                        <button type="submit" class="btn btn-sm btn-dark"><i class="bi bi-save me-1"></i>Save Footer</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <!-- PRIVACY TAB -->
              <div class="tab-pane fade" id="tab-privacy" role="tabpanel">
                <div class="card border-0 shadow">
                  <div class="card-body">
                    <h6 class="fw-bold mb-3">Privacy Policy</h6>
                    <form class="ajaxForm">
                      @csrf
                      <textarea name="privacy_content" class="form-control" rows="10">{{ $settings['privacy_content'] ?? '' }}</textarea>
                      <div class="text-end mt-3">
                        <button type="submit" class="btn btn-sm btn-dark"><i class="bi bi-save me-1"></i>Save Privacy</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <!-- TERMS TAB -->
              <div class="tab-pane fade" id="tab-terms" role="tabpanel">
                <div class="card border-0 shadow">
                  <div class="card-body">
                    <h6 class="fw-bold mb-3">Terms & Conditions</h6>
                    <form class="ajaxForm">
                      @csrf
                      <textarea name="terms_content" class="form-control" rows="10">{{ $settings['terms_content'] ?? '' }}</textarea>
                      <div class="text-end mt-3">
                        <button type="submit" class="btn btn-sm btn-dark"><i class="bi bi-save me-1"></i>Save Terms</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <!-- SEO TAB -->
              <div class="tab-pane fade" id="tab-seo" role="tabpanel">
                <div class="card border-0 shadow">
                  <div class="card-body">
                    <h6 class="fw-bold mb-3">SEO Defaults</h6>
                    <form class="ajaxForm">
                      @csrf
                      <div class="mb-3">
                        <label class="form-label small">Default Meta Title</label>
                        <input name="meta_title" value="{{ $settings['meta_title'] ?? '' }}" type="text" class="form-control form-control-sm">
                      </div>
                      <div class="mb-3">
                        <label class="form-label small">Default Meta Description</label>
                        <textarea name="meta_description" class="form-control form-control-sm" rows="3">{{ $settings['meta_description'] ?? '' }}</textarea>
                      </div>
                      <div class="text-end mt-3">
                        <button type="submit" class="btn btn-sm btn-dark"><i class="bi bi-save me-1"></i>Save SEO</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <!-- SOCIAL TAB -->
              <div class="tab-pane fade" id="tab-social" role="tabpanel">
                <div class="card border-0 shadow">
                  <div class="card-body">
                    <h6 class="fw-bold mb-3">Social Links</h6>
                    <form class="ajaxForm">
                      @csrf
                      <div class="row g-2">
                        <div class="col-md-6">
                          <label class="form-label small">Facebook</label>
                          <input name="social_facebook" value="{{ $settings['social_facebook'] ?? '' }}" type="text" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                          <label class="form-label small">Twitter / X</label>
                          <input name="social_twitter" value="{{ $settings['social_twitter'] ?? '' }}" type="text" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                          <label class="form-label small">Instagram</label>
                          <input name="social_instagram" value="{{ $settings['social_instagram'] ?? '' }}" type="text" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                          <label class="form-label small">YouTube</label>
                          <input name="social_youtube" value="{{ $settings['social_youtube'] ?? '' }}" type="text" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="text-end mt-3">
                        <button type="submit" class="btn btn-sm btn-dark"><i class="bi bi-save me-1"></i>Save Social</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <!-- SMTP TAB -->
              <div class="tab-pane fade" id="tab-smtp" role="tabpanel">
                <div class="card border-0 shadow">
                  <div class="card-body">
                    <h6 class="fw-bold mb-3">SMTP / Mail Settings</h6>
                    <form class="ajaxForm">
                      @csrf
                      <div class="row g-3">
                        <div class="col-md-6">
                          <label class="form-label small">Mail Driver</label>
                          <select name="mail_driver" class="form-select form-select-sm">
                            <option value="smtp" {{ ($settings['mail_driver']??'smtp')=='smtp' ? 'selected':'' }}>smtp</option>
                            <option value="sendmail" {{ ($settings['mail_driver']??'')=='sendmail' ? 'selected':'' }}>sendmail</option>
                            <option value="mailgun" {{ ($settings['mail_driver']??'')=='mailgun' ? 'selected':'' }}>mailgun</option>
                            <option value="ses" {{ ($settings['mail_driver']??'')=='ses' ? 'selected':'' }}>ses</option>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <label class="form-label small">Encryption</label>
                          <select name="mail_encryption" class="form-select form-select-sm">
                            <option value="tls" {{ ($settings['mail_encryption']??'tls')=='tls' ? 'selected':'' }}>tls</option>
                            <option value="ssl" {{ ($settings['mail_encryption']??'')=='ssl' ? 'selected':'' }}>ssl</option>
                            <option value="" {{ ($settings['mail_encryption']??'')=='' ? 'selected':'' }}>none</option>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <label class="form-label small">Mail Host</label>
                          <input name="mail_host" value="{{ $settings['mail_host'] ?? '' }}" type="text" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                          <label class="form-label small">Port</label>
                          <input name="mail_port" value="{{ $settings['mail_port'] ?? '' }}" type="text" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                          <label class="form-label small">From Address</label>
                          <input name="mail_from_address" value="{{ $settings['mail_from_address'] ?? '' }}" type="email" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                          <label class="form-label small">Username</label>
                          <input name="mail_username" value="{{ $settings['mail_username'] ?? '' }}" type="text" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                          <label class="form-label small">Password</label>
                          <input name="mail_password" value="{{ $settings['mail_password'] ?? '' }}" type="password" class="form-control form-control-sm">
                        </div>
                        <div class="col-12">
                          <label class="form-label small">From Name</label>
                          <input name="mail_from_name" value="{{ $settings['mail_from_name'] ?? '' }}" type="text" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="text-end mt-3">
                        <button type="submit" class="btn btn-sm btn-dark"><i class="bi bi-save me-1"></i>Save SMTP</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  // Universal AJAX Save for any form
  $(document).on('submit', '.ajaxForm', function(e){
    e.preventDefault();
    let form = $(this);
    let formData = new FormData(this);

    $.ajax({
      url: "{{ route('settings.ajax.save') }}",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      beforeSend: function(){
        form.find('button[type=submit]').prop('disabled', true).html('Saving...');
      },
      success: function(res){
        form.find('button[type=submit]').prop('disabled', false).html('Save');
        if(res.status === 'success'){
          showToast(res.message, 'success');
        }else{
          showToast('Something went wrong!', 'danger');
        }
      },
      error: function(err){
        form.find('button[type=submit]').prop('disabled', false).html('Save');
        showToast('Server error occurred!', 'danger');
      }
    });
  });

  // Logo Preview
  function previewLogo(e){
    const file = e.target.files[0];
    if(!file) return;
    const img = document.getElementById('logoImg');
    const text = document.getElementById('logoText');
    img.src = URL.createObjectURL(file);
    img.style.display = 'block';
    text.style.display = 'none';
  }

  // Toast function
  function showToast(message, type='success'){
    let toast = $('<div class="toast align-items-center text-white bg-'+type+' border-0" role="alert" aria-live="assertive" aria-atomic="true">'+
                    '<div class="d-flex">'+
                    '<div class="toast-body">'+message+'</div>'+
                    '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>'+
                    '</div></div>');
    $('#toastContainer').append(toast);
    var bsToast = new bootstrap.Toast(toast[0], { delay: 3000 });
    bsToast.show();
    toast.on('hidden.bs.toast', function(){ toast.remove(); });
  }
</script>

<div id="toastContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>
@endsection
