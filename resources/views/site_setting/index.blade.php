@extends('backend.layouts.master')
@section('title', 'Site Settings')

@section('main-content')

<div class="container-fluid py-4">
  <div class="row gx-4">
    <!-- LEFT SIDEBAR (vertical nav) -->
    <aside class="col-lg-3 mb-4">
      <div class="card shadow border-0 rounded-3" style="background: #212529">
        <div class="card-body p-3 text-white" style="background: #212529">
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
            <a class="nav-link text-white mb-1" data-bs-toggle="pill" href="#tab-privacy" role="tab"><i class="bi bi-shield-lock me-2"></i>Privacy Policy</a>
            <a class="nav-link text-white mb-1" data-bs-toggle="pill" href="#tab-terms" role="tab"><i class="bi bi-file-earmark-text me-2"></i>Terms</a>
            <a class="nav-link text-white mb-1" data-bs-toggle="pill" href="#tab-seo" role="tab"><i class="bi bi-search me-2"></i>SEO</a>
            <a class="nav-link text-white mb-1" data-bs-toggle="pill" href="#tab-social" role="tab"><i class="bi bi-share me-2"></i>Social Links</a>
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
          <div class="d-flex justify-content-between align-items-center px-4 py-3 border-bottom text-white" style="background: #212529">
            <div>
              <h5 class="mb-0 fw-bold">Settings</h5>
              <small class="text-muted">Configure site-wide options</small>
            </div>
            <div>
              <!-- global save button (optional) -->
              <button id="saveAllBtn" class="btn btn-sm btn-outline-secondary me-2" type="button"><i class="bi bi-arrow-repeat me-1"></i>Reset</button>
              <button id="saveActiveBtn" class="btn btn-sm btn-primary" type="button"><i class="bi bi-save me-1"></i>Save</button>
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

                        <form id="form-general" action="#" method="POST" enctype="multipart/form-data">
                          @csrf
                          <div class="mb-3">
                            <label class="form-label small">Logo</label>
                            <div class="d-flex gap-3 align-items-center">
                              <div id="logoPreview" class="border rounded-3 d-flex align-items-center justify-content-center" style="width:120px;height:80px;background:#f8f9fa;">
                                <img id="logoImg" src="{{ old('logo_preview') ?? '' }}" alt="logo" style="max-width:100%;max-height:100%;display:none;">
                                <span id="logoText" class="text-muted small">No logo</span>
                              </div>
                              <div class="flex-fill">
                                <input type="file" name="site_logo" class="form-control form-control-sm" accept="image/*" onchange="previewLogo(event)">
                                <small class="text-muted">Recommended: 300x100 px, PNG or SVG</small>
                              </div>
                            </div>
                          </div>

                          <div class="mb-3">
                            <label class="form-label small">Site Name</label>
                            <input name="site_name" value="{{ old('site_name') }}" type="text" class="form-control form-control-sm" placeholder="My Awesome Site">
                          </div>

                          <div class="mb-3">
                            <label class="form-label small">Tagline</label>
                            <input name="site_tagline" value="{{ old('site_tagline') }}" type="text" class="form-control form-control-sm" placeholder="Short tagline">
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
                        <p class="text-muted small">This panel can show quick previews, current values, or helpful tips.</p>

                        <dl class="row">
                          <dt class="col-sm-4 small text-muted">Current Site Name</dt>
                          <dd class="col-sm-8 small"> <strong id="currentSiteName">{{ old('site_name') ?? '—' }}</strong></dd>

                          <dt class="col-sm-4 small text-muted">Tagline</dt>
                          <dd class="col-sm-8 small small" id="currentTagline">{{ old('site_tagline') ?? '—' }}</dd>

                          <dt class="col-sm-4 small text-muted">Logo</dt>
                          <dd class="col-sm-8 small" id="currentLogoText">{{ old('site_logo') ? 'Uploaded' : 'No logo' }}</dd>
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

                    <form id="form-header" action="#" method="POST">
                      @csrf
                      <div class="row g-3">
                        <div class="col-md-6">
                          <label class="form-label small">Show Search</label>
                          <select name="header_search" class="form-select form-select-sm">
                            <option value="1" {{ old('header_search')=='1' ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ old('header_search')=='0' ? 'selected' : '' }}>No</option>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <label class="form-label small">Show Contact Link</label>
                          <select name="header_contact" class="form-select form-select-sm">
                            <option value="1" {{ old('header_contact')=='1' ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ old('header_contact')=='0' ? 'selected' : '' }}>No</option>
                          </select>
                        </div>
                      </div>

                      <div class="mt-3 text-end">
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

                    <form id="form-footer" action="#" method="POST">
                      @csrf
                      <div class="mb-3">
                        <label class="form-label small">Footer Text</label>
                        <textarea name="footer_text" rows="3" class="form-control form-control-sm">{{ old('footer_text') }}</textarea>
                      </div>

                      <div class="mb-3">
                        <label class="form-label small">Footer Links (JSON)</label>
                        <textarea name="footer_links" rows="3" class="form-control form-control-sm" placeholder='[{"title":"About","url":"/about"}]'>{{ old('footer_links') }}</textarea>
                      </div>

                      <div class="text-end">
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

                    <form id="form-privacy" action="#" method="POST">
                      @csrf
                      <textarea name="privacy_content" class="form-control" rows="10">{{ old('privacy_content') }}</textarea>

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

                    <form id="form-terms" action="#" method="POST">
                      @csrf
                      <textarea name="terms_content" class="form-control" rows="10">{{ old('terms_content') }}</textarea>

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

                    <form id="form-seo" action="#" method="POST">
                      @csrf
                      <div class="mb-3">
                        <label class="form-label small">Default Meta Title</label>
                        <input name="meta_title" value="{{ old('meta_title') }}" type="text" class="form-control form-control-sm">
                      </div>

                      <div class="mb-3">
                        <label class="form-label small">Default Meta Description</label>
                        <textarea name="meta_description" class="form-control form-control-sm" rows="3">{{ old('meta_description') }}</textarea>
                      </div>

                      <div class="text-end">
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

                    <form id="form-social" action="#" method="POST">
                      @csrf
                      <div class="row g-2">
                        <div class="col-md-6">
                          <label class="form-label small">Facebook</label>
                          <input name="social_facebook" value="{{ old('social_facebook') }}" type="text" class="form-control form-control-sm" placeholder="https://facebook.com/yourpage">
                        </div>
                        <div class="col-md-6">
                          <label class="form-label small">Twitter / X</label>
                          <input name="social_twitter" value="{{ old('social_twitter') }}" type="text" class="form-control form-control-sm" placeholder="https://x.com/yourhandle">
                        </div>
                        <div class="col-md-6">
                          <label class="form-label small">Instagram</label>
                          <input name="social_instagram" value="{{ old('social_instagram') }}" type="text" class="form-control form-control-sm" placeholder="https://instagram.com/yourprofile">
                        </div>
                        <div class="col-md-6">
                          <label class="form-label small">YouTube</label>
                          <input name="social_youtube" value="{{ old('social_youtube') }}" type="text" class="form-control form-control-sm" placeholder="https://youtube.com/channel/...">
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

                    <form id="form-smtp" action="$" method="POST">
                      @csrf

                      <div class="row g-3">
                        <div class="col-md-6">
                          <label class="form-label small">Mail Driver</label>
                          <select name="mail_driver" class="form-select form-select-sm">
                            <option value="smtp" {{ old('mail_driver')=='smtp' ? 'selected':'' }}>smtp</option>
                            <option value="sendmail" {{ old('mail_driver')=='sendmail' ? 'selected':'' }}>sendmail</option>
                            <option value="mailgun" {{ old('mail_driver')=='mailgun' ? 'selected':'' }}>mailgun</option>
                            <option value="ses" {{ old('mail_driver')=='ses' ? 'selected':'' }}>ses</option>
                          </select>
                        </div>

                        <div class="col-md-6">
                          <label class="form-label small">Encryption</label>
                          <select name="mail_encryption" class="form-select form-select-sm">
                            <option value="tls" {{ old('mail_encryption')=='tls' ? 'selected':'' }}>tls</option>
                            <option value="ssl" {{ old('mail_encryption')=='ssl' ? 'selected':'' }}>ssl</option>
                            <option value="" {{ old('mail_encryption')=='' ? 'selected':'' }}>none</option>
                          </select>
                        </div>

                        <div class="col-md-6">
                          <label class="form-label small">Mail Host</label>
                          <input name="mail_host" value="{{ old('mail_host') }}" type="text" class="form-control form-control-sm" placeholder="smtp.mailtrap.io">
                        </div>

                        <div class="col-md-3">
                          <label class="form-label small">Port</label>
                          <input name="mail_port" value="{{ old('mail_port') }}" type="text" class="form-control form-control-sm" placeholder="587">
                        </div>

                        <div class="col-md-3">
                          <label class="form-label small">From Address</label>
                          <input name="mail_from_address" value="{{ old('mail_from_address') }}" type="email" class="form-control form-control-sm" placeholder="noreply@yourdomain.com">
                        </div>

                        <div class="col-md-6">
                          <label class="form-label small">Username</label>
                          <input name="mail_username" value="{{ old('mail_username') }}" type="text" class="form-control form-control-sm">
                        </div>

                        <div class="col-md-6">
                          <label class="form-label small">Password</label>
                          <input name="mail_password" value="{{ old('mail_password') }}" type="password" class="form-control form-control-sm">
                        </div>

                        <div class="col-12">
                          <label class="form-label small">From Name</label>
                          <input name="mail_from_name" value="{{ old('mail_from_name') }}" type="text" class="form-control form-control-sm" placeholder="Your Company">
                        </div>
                      </div>

                      <div class="text-end mt-3">
                        <button type="submit" class="btn btn-sm btn-dark"><i class="bi bi-save me-1"></i>Save SMTP</button>
                      </div>
                    </form>

                    <div class="mt-3">
                      <small class="text-muted">Note: After saving SMTP, you can test sending email via your own test route/controller.</small>
                    </div>
                  </div>
                </div>
              </div>

              <!-- end tab-content -->
            </div>
          </div>

        </div>
      </div>
    </section>
  </div>
</div>
@endsection

@section('scripts')
<script>
  // Logo preview
  function previewLogo(e) {
    const file = e.target.files && e.target.files[0];
    if (!file) return;
    const img = document.getElementById('logoImg');
    const text = document.getElementById('logoText');
    img.src = URL.createObjectURL(file);
    img.style.display = 'block';
    text.style.display = 'none';
    // update quick preview text
    document.getElementById('currentLogoText').textContent = 'Uploaded';
  }

  // Sidebar nav active handling (Bootstrap pills handle class toggling, but we ensure focus)
  document.querySelectorAll('#settingsSidebar .nav-link').forEach(link => {
    link.addEventListener('shown.bs.tab', (e) => {
      // nothing for now; placeholder if you want to load content async
    });
  });

  // Save button: trigger currently visible tab's form submit
  document.getElementById('saveActiveBtn').addEventListener('click', function () {
    // find active tab pane and submit first form inside it
    const activePane = document.querySelector('.tab-pane.active, .tab-pane.show.active') || document.querySelector('.tab-pane.show');
    if (!activePane) return;
    const form = activePane.querySelector('form');
    if (form) {
      form.submit();
    } else {
      // no form: show small success flash (you can replace with better toast)
      alert('Nothing to save on this tab.');
    }
  });

  // Reset button: will reset visible form fields only
  document.getElementById('saveAllBtn').addEventListener('click', function () {
    if (!confirm('Reset visible fields?')) return;
    const forms = document.querySelectorAll('.tab-pane form');
    forms.forEach(f => f.reset());
    // also hide logo preview
    const img = document.getElementById('logoImg');
    if (img) { img.style.display = 'none'; document.getElementById('logoText').style.display = 'block'; }
  });
</script>
@endsection
