@extends('layouts.public')

@php
  $lang = request()->query('lang', 'ru');
  $lang = in_array($lang, ['kk', 'ru', 'en'], true) ? $lang : 'ru';
  $activePage = $activePage ?? 'contacts';

  $routeWithLang = static function (string $path, array $query = []) use ($lang): string {
      if ($lang !== 'ru' && ! array_key_exists('lang', $query)) {
          $query['lang'] = $lang;
      }
      $qs = http_build_query(array_filter($query, static fn ($v) => $v !== null && $v !== ''));
      return $path . ($qs !== '' ? ('?' . $qs) : '');
  };

  $copy = $contacts[$lang];
  $chromeTitle = [
      'ru' => 'Контакты — KazUTB Smart Library',
      'kk' => 'Байланыс — KazUTB Smart Library',
      'en' => 'Contacts — KazUTB Smart Library',
  ][$lang];
@endphp

@section('title', $chromeTitle)

@section('content')
  <div class="contacts-canonical">
    {{-- Hero — display heading + lead paragraph. --}}
    <section class="contacts-canonical__section contacts-canonical__hero" data-section="contacts-canonical-hero">
      <div class="contacts-canonical__hero-glow" aria-hidden="true"></div>
      <h1 class="contacts-canonical__display">
        {{ $copy['hero_title_a'] }}<br>
        <span class="contacts-canonical__display-accent">{{ $copy['hero_title_b'] }}</span>
      </h1>
      <p class="contacts-canonical__lead">{{ $copy['hero_body'] }}</p>
    </section>

    {{-- Support Channels (7/12) + Inquiry Form + Location (5/12). --}}
    <div class="contacts-canonical__grid">
      <section class="contacts-canonical__col-left" data-section="contacts-canonical-support">
        <h2 class="contacts-canonical__section-heading">{{ $copy['support_heading'] }}</h2>
        <div class="contacts-canonical__channels">
          @foreach($copy['support_channels'] as $channel)
            <article class="contacts-canonical__channel-card" data-support-channel data-channel-slug="{{ $channel['slug'] }}">
              <div class="contacts-canonical__channel-icon" aria-hidden="true">
                <span class="material-symbols-outlined">{{ $channel['icon'] }}</span>
              </div>
              <div class="contacts-canonical__channel-body">
                <h3 class="contacts-canonical__channel-title">{{ $channel['title'] }}</h3>
                <p class="contacts-canonical__channel-desc">{{ $channel['body'] }}</p>
                <div class="contacts-canonical__channel-contacts">
                  <div class="contacts-canonical__channel-contact">
                    <span class="material-symbols-outlined" aria-hidden="true">mail</span>
                    <a href="mailto:{{ $channel['email'] }}" data-test-id="contacts-canonical-channel-email-{{ $channel['slug'] }}">{{ $channel['email'] }}</a>
                  </div>
                  <div class="contacts-canonical__channel-contact">
                    <span class="material-symbols-outlined" aria-hidden="true">call</span>
                    <span>{{ $channel['phone'] }}</span>
                  </div>
                </div>
              </div>
            </article>
          @endforeach
        </div>
      </section>

      <aside class="contacts-canonical__col-right">
        {{-- Inquiry form — UI-only surface; submit opens mailto to main library inbox. --}}
        <section class="contacts-canonical__form-card" data-section="contacts-canonical-inquiry-form">
          <h3 class="contacts-canonical__card-heading">{{ $copy['form_title'] }}</h3>
          <p class="contacts-canonical__form-note">{{ $copy['form_note'] }}</p>
          <form class="contacts-canonical__form" action="mailto:{{ $copy['location_email'] }}" method="post" enctype="text/plain" data-test-id="contacts-canonical-inquiry-form">
            <div class="contacts-canonical__field">
              <label for="contact-name">{{ $copy['form_label_name'] }}</label>
              <input type="text" id="contact-name" name="name" placeholder="{{ $copy['form_placeholder_name'] }}" autocomplete="name">
            </div>
            <div class="contacts-canonical__field">
              <label for="contact-email">{{ $copy['form_label_email'] }}</label>
              <input type="email" id="contact-email" name="email" placeholder="{{ $copy['form_placeholder_email'] }}" autocomplete="email">
            </div>
            <div class="contacts-canonical__field">
              <label for="contact-department">{{ $copy['form_label_department'] }}</label>
              <select id="contact-department" name="department">
                <option value="" disabled selected>{{ $copy['form_placeholder_department'] }}</option>
                @foreach($copy['form_departments'] as $optValue => $optLabel)
                  <option value="{{ $optValue }}">{{ $optLabel }}</option>
                @endforeach
              </select>
            </div>
            <div class="contacts-canonical__field">
              <label for="contact-message">{{ $copy['form_label_message'] }}</label>
              <textarea id="contact-message" name="message" rows="4" placeholder="{{ $copy['form_placeholder_message'] }}"></textarea>
            </div>
            <button type="submit" class="contacts-canonical__form-submit" data-test-id="contacts-canonical-inquiry-submit">
              <span>{{ $copy['form_submit'] }}</span>
              <span class="material-symbols-outlined" aria-hidden="true">send</span>
            </button>
          </form>
        </section>

        {{-- Location + hours card. --}}
        <section class="contacts-canonical__location-card" data-section="contacts-canonical-location">
          <h4 class="contacts-canonical__card-heading contacts-canonical__card-heading--with-icon">
            <span class="material-symbols-outlined" aria-hidden="true">location_on</span>
            {{ $copy['location_title'] }}
          </h4>
          <p class="contacts-canonical__location-body">
            {{ $copy['location_address_line_a'] }}<br>
            {{ $copy['location_address_line_b'] }}
          </p>
          <div class="contacts-canonical__location-contact">
            <div class="contacts-canonical__channel-contact">
              <span class="material-symbols-outlined" aria-hidden="true">call</span>
              <span>{{ $copy['location_phone'] }}</span>
            </div>
            <div class="contacts-canonical__channel-contact">
              <span class="material-symbols-outlined" aria-hidden="true">mail</span>
              <a href="mailto:{{ $copy['location_email'] }}">{{ $copy['location_email'] }}</a>
            </div>
          </div>
          <a class="contacts-canonical__directions-link"
             href="https://www.google.com/maps/search/?api=1&amp;query={{ urlencode($copy['location_address_line_a'] . ', ' . $copy['location_address_line_b']) }}"
             target="_blank"
             rel="noopener noreferrer"
             data-test-id="contacts-canonical-directions">
            <span class="material-symbols-outlined" aria-hidden="true">directions</span>
            {{ $copy['location_directions_cta'] }}
          </a>
          <div class="contacts-canonical__hours">
            <p class="contacts-canonical__hours-label">{{ $copy['hours_label'] }}</p>
            @foreach($copy['hours_rows'] as $row)
              <div class="contacts-canonical__hours-row">
                <span>{{ $row['days'] }}</span>
                <span>{{ $row['hours'] }}</span>
              </div>
            @endforeach
          </div>
        </section>
      </aside>
    </div>

    {{-- Fund Guidance & Wayfinding (canonical location_fund_rooms pattern). --}}
    <section class="contacts-canonical__section contacts-canonical__wayfinding" data-section="contacts-canonical-fund-guidance">
      <div class="contacts-canonical__wayfinding-head">
        <span class="material-symbols-outlined" aria-hidden="true">account_tree</span>
        <h2 class="contacts-canonical__section-heading">{{ $copy['wayfinding_title'] }}</h2>
      </div>
      <p class="contacts-canonical__wayfinding-body">{{ $copy['wayfinding_body'] }}</p>

      <figure class="contacts-canonical__map" aria-label="{{ $copy['map_label'] }}" data-test-id="contacts-canonical-map">
        <div class="contacts-canonical__map-canvas" role="img" aria-hidden="true">
          <span class="contacts-canonical__map-pin">📍</span>
          <span class="contacts-canonical__map-label">{{ $copy['map_label'] }}</span>
        </div>
        <figcaption>{{ $copy['map_caption'] }}</figcaption>
      </figure>

      <div class="contacts-canonical__rooms">
        @foreach($copy['fund_rooms'] as $room)
          <article class="contacts-canonical__room" data-fund-room-slot data-room-code="{{ $room['room'] }}">
            <header class="contacts-canonical__room-head">
              <h4 class="contacts-canonical__room-code">{{ $copy['room_prefix'] }} {{ $room['room'] }}</h4>
              <span class="contacts-canonical__room-level">{{ $room['floor'] }}</span>
            </header>
            <p class="contacts-canonical__room-fund">{{ $room['fund_label'] }}</p>
            <p class="contacts-canonical__room-desc">{{ $room['short_description'] }}</p>
            <p class="contacts-canonical__room-access">
              <span class="material-symbols-outlined" aria-hidden="true">info</span>
              {{ $room['access_note'] }}
            </p>
          </article>
        @endforeach
      </div>
    </section>

    {{-- Visit guidance + cross-links to /rules and /leadership. --}}
    <section class="contacts-canonical__section contacts-canonical__visit" data-section="contacts-canonical-visit-rules">
      <div class="contacts-canonical__visit-grid">
        <div class="contacts-canonical__visit-copy">
          <h3 class="contacts-canonical__section-heading">{{ $copy['visit_title'] }}</h3>
          <p class="contacts-canonical__visit-body">{{ $copy['visit_body'] }}</p>
        </div>
        <ul class="contacts-canonical__visit-links">
          <li>
            <a class="contacts-canonical__visit-link" href="{{ $routeWithLang('/rules') }}" data-test-id="contacts-canonical-link-rules">
              <span class="contacts-canonical__visit-link-title">{{ $copy['visit_link_rules_title'] }}</span>
              <span class="contacts-canonical__visit-link-body">{{ $copy['visit_link_rules_body'] }}</span>
              <span class="material-symbols-outlined contacts-canonical__visit-link-arrow" aria-hidden="true">arrow_forward</span>
            </a>
          </li>
          <li>
            <a class="contacts-canonical__visit-link" href="{{ $routeWithLang('/leadership') }}" data-test-id="contacts-canonical-link-leadership">
              <span class="contacts-canonical__visit-link-title">{{ $copy['visit_link_leadership_title'] }}</span>
              <span class="contacts-canonical__visit-link-body">{{ $copy['visit_link_leadership_body'] }}</span>
              <span class="material-symbols-outlined contacts-canonical__visit-link-arrow" aria-hidden="true">arrow_forward</span>
            </a>
          </li>
        </ul>
      </div>
    </section>
  </div>
@endsection

@section('head')
<style>
  .contacts-canonical {
    max-width: 1280px;
    margin: 0 auto;
    padding: 80px 16px 96px;
    color: #191c1d;
    font-family: 'Manrope', sans-serif;
  }

  @media (min-width: 768px) {
    .contacts-canonical {
      padding: 96px 24px 96px;
    }
  }

  @media (min-width: 1024px) {
    .contacts-canonical {
      padding-left: 32px;
      padding-right: 32px;
    }
  }

  .contacts-canonical__section {
    margin-bottom: 80px;
  }

  .contacts-canonical__hero {
    position: relative;
    padding-top: 32px;
  }

  .contacts-canonical__hero-glow {
    position: absolute;
    top: 0;
    right: 0;
    width: 256px;
    height: 256px;
    background: rgba(0, 31, 63, 0.05);
    border-radius: 9999px;
    filter: blur(48px);
    z-index: -1;
    pointer-events: none;
  }

  .contacts-canonical__display {
    font-family: 'Newsreader', serif;
    font-weight: 300;
    font-size: 44px;
    line-height: 1.08;
    letter-spacing: -0.02em;
    color: #000613;
    margin: 0 0 24px -2px;
  }

  @media (min-width: 768px) {
    .contacts-canonical__display {
      font-size: 56px;
    }
  }

  .contacts-canonical__display-accent {
    color: #001f3f;
    font-style: italic;
  }

  .contacts-canonical__lead {
    font-family: 'Manrope', sans-serif;
    font-size: 16px;
    line-height: 1.65;
    color: #43474e;
    max-width: 640px;
    margin: 0;
  }

  .contacts-canonical__grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 32px;
    margin-bottom: 80px;
  }

  @media (min-width: 1024px) {
    .contacts-canonical__grid {
      grid-template-columns: 7fr 5fr;
      gap: 48px;
    }
  }

  .contacts-canonical__section-heading {
    font-family: 'Newsreader', serif;
    font-size: 28px;
    line-height: 1.2;
    color: #000613;
    margin: 0 0 32px;
  }

  .contacts-canonical__col-left {
    display: flex;
    flex-direction: column;
  }

  .contacts-canonical__channels {
    display: flex;
    flex-direction: column;
    gap: 24px;
  }

  .contacts-canonical__channel-card {
    background: #ffffff;
    border-radius: 8px;
    padding: 32px;
    transition: background-color 0.3s ease;
    display: flex;
    flex-direction: row;
    align-items: flex-start;
    gap: 24px;
  }

  .contacts-canonical__channel-card:hover {
    background: #f3f4f5;
  }

  .contacts-canonical__channel-icon {
    flex-shrink: 0;
    width: 56px;
    height: 56px;
    border-radius: 9999px;
    background: #f8f9fa;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #006a6a;
    transition: background-color 0.3s ease, color 0.3s ease;
  }

  .contacts-canonical__channel-card:hover .contacts-canonical__channel-icon {
    background: #90efef;
    color: #006e6e;
  }

  .contacts-canonical__channel-icon .material-symbols-outlined {
    font-size: 28px;
  }

  .contacts-canonical__channel-body {
    flex: 1;
  }

  .contacts-canonical__channel-title {
    font-family: 'Manrope', sans-serif;
    font-weight: 700;
    font-size: 20px;
    line-height: 1.3;
    color: #000613;
    margin: 0 0 8px;
  }

  .contacts-canonical__channel-desc {
    font-family: 'Manrope', sans-serif;
    font-size: 15px;
    line-height: 1.6;
    color: #43474e;
    margin: 0 0 20px;
  }

  .contacts-canonical__channel-contacts {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-top: 16px;
  }

  @media (min-width: 640px) {
    .contacts-canonical__channel-contacts {
      flex-direction: row;
      gap: 24px;
    }
  }

  .contacts-canonical__channel-contact {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #191c1d;
  }

  .contacts-canonical__channel-contact .material-symbols-outlined {
    font-size: 18px;
    color: #74777f;
  }

  .contacts-canonical__channel-contact a {
    color: inherit;
    text-decoration: none;
    border-bottom: 1px solid transparent;
    transition: border-color 0.2s ease;
  }

  .contacts-canonical__channel-contact a:hover {
    border-color: #006a6a;
  }

  .contacts-canonical__col-right {
    display: flex;
    flex-direction: column;
    gap: 48px;
  }

  .contacts-canonical__form-card {
    background: #f3f4f5;
    padding: 32px;
    border-radius: 12px;
  }

  .contacts-canonical__card-heading {
    font-family: 'Newsreader', serif;
    font-size: 24px;
    line-height: 1.25;
    color: #000613;
    margin: 0 0 16px;
    display: block;
  }

  .contacts-canonical__card-heading--with-icon {
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }

  .contacts-canonical__card-heading--with-icon .material-symbols-outlined {
    color: #006a6a;
    font-size: 24px;
  }

  .contacts-canonical__form-note {
    font-family: 'Manrope', sans-serif;
    font-size: 13px;
    color: #43474e;
    margin: 0 0 20px;
    line-height: 1.5;
  }

  .contacts-canonical__form {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }

  .contacts-canonical__field {
    display: flex;
    flex-direction: column;
  }

  .contacts-canonical__field label {
    font-family: 'Manrope', sans-serif;
    font-size: 12px;
    font-weight: 500;
    color: #191c1d;
    margin-bottom: 6px;
  }

  .contacts-canonical__field input,
  .contacts-canonical__field select,
  .contacts-canonical__field textarea {
    width: 100%;
    background: #e1e3e4;
    border: 0;
    border-bottom: 1px solid rgba(196, 198, 207, 0.35);
    border-radius: 6px 6px 0 0;
    padding: 12px 16px;
    font-family: 'Manrope', sans-serif;
    font-size: 15px;
    color: #191c1d;
    transition: border-color 0.2s ease;
  }

  .contacts-canonical__field textarea {
    resize: vertical;
    min-height: 96px;
  }

  .contacts-canonical__field input:focus,
  .contacts-canonical__field select:focus,
  .contacts-canonical__field textarea:focus {
    outline: none;
    border-bottom-color: #006a6a;
  }

  .contacts-canonical__form-submit {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 14px 24px;
    border-radius: 6px;
    border: 0;
    background: linear-gradient(to right, #000613, #001f3f);
    color: #ffffff;
    font-family: 'Manrope', sans-serif;
    font-size: 15px;
    font-weight: 500;
    cursor: pointer;
    transition: opacity 0.2s ease;
  }

  .contacts-canonical__form-submit:hover {
    opacity: 0.9;
  }

  .contacts-canonical__form-submit .material-symbols-outlined {
    font-size: 18px;
  }

  .contacts-canonical__location-card {
    background: #ffffff;
    padding: 24px;
    border-radius: 8px;
  }

  .contacts-canonical__location-body {
    font-family: 'Manrope', sans-serif;
    font-size: 15px;
    line-height: 1.6;
    color: #43474e;
    margin: 0 0 20px;
  }

  .contacts-canonical__location-contact {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 16px;
  }

  .contacts-canonical__directions-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-family: 'Manrope', sans-serif;
    font-size: 14px;
    font-weight: 500;
    color: #006a6a;
    text-decoration: none;
    padding: 8px 0;
    border-bottom: 1px solid transparent;
    transition: border-color 0.2s ease;
  }

  .contacts-canonical__directions-link:hover {
    border-color: #006a6a;
  }

  .contacts-canonical__directions-link .material-symbols-outlined {
    font-size: 18px;
  }

  .contacts-canonical__hours {
    margin-top: 20px;
    padding-top: 16px;
    border-top: 1px solid rgba(196, 198, 207, 0.35);
  }

  .contacts-canonical__hours-label {
    font-family: 'Manrope', sans-serif;
    font-size: 12px;
    font-weight: 600;
    color: #191c1d;
    margin: 0 0 12px;
    text-transform: uppercase;
    letter-spacing: 0.08em;
  }

  .contacts-canonical__hours-row {
    display: flex;
    justify-content: space-between;
    font-family: 'Manrope', sans-serif;
    font-size: 14px;
    color: #43474e;
    line-height: 1.6;
  }

  .contacts-canonical__wayfinding {
    background: #ffffff;
    border-radius: 12px;
    padding: 32px;
  }

  @media (min-width: 768px) {
    .contacts-canonical__wayfinding {
      padding: 40px;
    }
  }

  .contacts-canonical__wayfinding-head {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 8px;
  }

  .contacts-canonical__wayfinding-head .material-symbols-outlined {
    font-size: 28px;
    color: #000613;
  }

  .contacts-canonical__wayfinding-head .contacts-canonical__section-heading {
    margin: 0;
  }

  .contacts-canonical__wayfinding-body {
    font-family: 'Manrope', sans-serif;
    font-size: 15px;
    line-height: 1.6;
    color: #43474e;
    margin: 0 0 32px;
    max-width: 720px;
  }

  .contacts-canonical__map {
    margin: 0 0 32px;
    padding: 0;
    border-radius: 12px;
    overflow: hidden;
    background: #f3f4f5;
  }

  .contacts-canonical__map-canvas {
    aspect-ratio: 16 / 9;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    background: linear-gradient(135deg, #e7e8e9 0%, #d4e3ff 60%, #afc8f0 100%);
    color: #001f3f;
  }

  .contacts-canonical__map-pin {
    font-size: 40px;
  }

  .contacts-canonical__map-label {
    font-family: 'Manrope', sans-serif;
    font-size: 15px;
    font-weight: 600;
  }

  .contacts-canonical__map figcaption {
    padding: 16px 20px;
    font-family: 'Manrope', sans-serif;
    font-size: 13px;
    color: #43474e;
  }

  .contacts-canonical__rooms {
    display: grid;
    grid-template-columns: 1fr;
    gap: 32px;
  }

  @media (min-width: 768px) {
    .contacts-canonical__rooms {
      grid-template-columns: repeat(3, 1fr);
    }
  }

  .contacts-canonical__room {
    border-top: 1px solid rgba(116, 119, 127, 0.2);
    padding-top: 20px;
  }

  .contacts-canonical__room-head {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    padding-bottom: 12px;
    margin-bottom: 12px;
    border-bottom: 1px solid rgba(116, 119, 127, 0.2);
  }

  .contacts-canonical__room-code {
    font-family: 'Manrope', sans-serif;
    font-weight: 700;
    font-size: 17px;
    color: #000613;
    margin: 0;
  }

  .contacts-canonical__room-level {
    background: #edeeef;
    padding: 4px 12px;
    border-radius: 9999px;
    font-family: 'Manrope', sans-serif;
    font-size: 12px;
    font-weight: 500;
    color: #43474e;
  }

  .contacts-canonical__room-fund {
    font-family: 'Manrope', sans-serif;
    font-size: 15px;
    color: #43474e;
    margin: 0 0 8px;
    line-height: 1.5;
  }

  .contacts-canonical__room-desc {
    font-family: 'Manrope', sans-serif;
    font-size: 13px;
    color: #76889d;
    line-height: 1.5;
    margin: 0 0 12px;
  }

  .contacts-canonical__room-access {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-family: 'Manrope', sans-serif;
    font-size: 12px;
    color: #006a6a;
    margin: 0;
    line-height: 1.4;
  }

  .contacts-canonical__room-access .material-symbols-outlined {
    font-size: 16px;
  }

  .contacts-canonical__visit {
    background: #f3f4f5;
    border-radius: 12px;
    padding: 32px;
  }

  @media (min-width: 768px) {
    .contacts-canonical__visit {
      padding: 48px;
    }
  }

  .contacts-canonical__visit-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 32px;
  }

  @media (min-width: 1024px) {
    .contacts-canonical__visit-grid {
      grid-template-columns: 1fr 1fr;
      gap: 48px;
      align-items: start;
    }
  }

  .contacts-canonical__visit-body {
    font-family: 'Manrope', sans-serif;
    font-size: 15px;
    line-height: 1.6;
    color: #43474e;
    margin: 0;
    max-width: 480px;
  }

  .contacts-canonical__visit-links {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 16px;
  }

  .contacts-canonical__visit-link {
    display: grid;
    grid-template-columns: 1fr auto;
    grid-template-rows: auto auto;
    row-gap: 4px;
    column-gap: 16px;
    align-items: center;
    padding: 20px 24px;
    background: #ffffff;
    border-radius: 8px;
    text-decoration: none;
    color: #000613;
    transition: background-color 0.2s ease;
  }

  .contacts-canonical__visit-link:hover {
    background: #000613;
    color: #ffffff;
  }

  .contacts-canonical__visit-link-title {
    font-family: 'Newsreader', serif;
    font-size: 20px;
    font-weight: 500;
    grid-column: 1;
    grid-row: 1;
  }

  .contacts-canonical__visit-link-body {
    font-family: 'Manrope', sans-serif;
    font-size: 13px;
    color: inherit;
    opacity: 0.75;
    grid-column: 1;
    grid-row: 2;
  }

  .contacts-canonical__visit-link-arrow {
    grid-column: 2;
    grid-row: 1 / span 2;
    font-size: 20px;
    transition: transform 0.2s ease;
  }

  .contacts-canonical__visit-link:hover .contacts-canonical__visit-link-arrow {
    transform: translateX(4px);
  }
</style>
@endsection
@extends('layouts.public')

@php
  $lang = request()->query('lang', 'ru');
  $lang = in_array($lang, ['kk', 'ru', 'en'], true) ? $lang : 'ru';
  $routeWithLang = static function (string $path) use ($lang): string {
    if ($lang === 'ru') {
      return $path;
    }

    $separator = str_contains($path, '?') ? '&' : '?';
    return $path . $separator . 'lang=' . $lang;
  };

  $copy = [
    'ru' => [
      'meta' => 'О библиотеке и контакты — Digital Library',
      'hero' => 'О библиотеке и контакты',
      'lead' => 'Digital Library — центр знаний и информационной поддержки для студентов, преподавателей и исследователей.',
      'mission_label' => 'Миссия',
      'mission_title' => 'Доступ к знаниям и информационным ресурсам',
      'mission_body' => 'Digital Library поддерживает учебу и исследования, развивает фонд, расширяет цифровые подписки и создаёт комфортные условия для работы с информацией.',
      'contacts' => 'Контакты',
      'contacts_help' => 'Как связаться с библиотекой.',
      'hours' => 'Режим работы',
      'hours_help' => 'График работы библиотеки.',
      'units' => 'Подразделения',
      'units_help' => 'Контакты подразделений библиотеки.',
    ],
    'kk' => [
      'meta' => 'Кітапхана туралы және байланыс — Digital Library',
      'hero' => 'Кітапхана туралы және байланыс',
      'lead' => 'Digital Library студенттер, оқытушылар және зерттеушілер үшін білім мен ақпараттық қолдаудың заманауи орталығы болып табылады.',
      'mission_label' => 'Миссия',
      'mission_title' => 'Білім мен ақпараттық ресурстарға қолжетімділік',
      'mission_body' => 'Digital Library оқу үдерісі мен зерттеулерді ақпараттық тұрғыда қолдайды. Қорды дамытып, цифрлық жазылымдарды кеңейтіп, пайдаланушыларға ыңғайлы орта қалыптастырамыз.',
      'contacts' => 'Байланыс',
      'contacts_help' => 'Кітапханамен қалай байланысуға болады.',
      'hours' => 'Жұмыс уақыты',
      'hours_help' => 'Кітапхананың жұмыс кестесі.',
      'units' => 'Бөлімдер',
      'units_help' => 'Кітапхана бөлімдерінің байланыстары.',
    ],
    'en' => [
      'meta' => 'About the library and contacts — Digital Library',
      'hero' => 'About the library and contacts',
      'lead' => 'Digital Library is a modern knowledge and information-support center for students, faculty, and researchers.',
      'mission_label' => 'Mission',
      'mission_title' => 'Access to knowledge and information resources',
      'mission_body' => 'Digital Library supports study and research through a growing collection, digital subscriptions, and comfortable access for readers.',
      'contacts' => 'Contacts',
      'contacts_help' => 'How to reach the library.',
      'hours' => 'Opening hours',
      'hours_help' => 'The current library schedule.',
      'units' => 'Library units',
      'units_help' => 'Contact points across the library services.',
    ],
  ][$lang];
@endphp

@section('title', $copy['meta'])

@section('content')
  <section class="page-hero contact-hero">
    <div class="container contact-hero-shell">
      <div>
        <div class="eyebrow eyebrow--cyan">{{ ['ru' => 'Университетская библиотека', 'kk' => 'Университет кітапханасы', 'en' => 'Institutional library'][$lang] }}</div>
        <h1>{{ $copy['hero'] }}</h1>
        <p>{{ $copy['lead'] }}</p>
        <div class="contact-hero-actions">
          <a href="{{ $routeWithLang('/catalog') }}" class="btn btn-primary">{{ ['ru' => 'Каталог', 'kk' => 'Каталог', 'en' => 'Catalog'][$lang] }}</a>
          <a href="{{ $routeWithLang('/resources') }}" class="btn btn-ghost">{{ ['ru' => 'Ресурсы', 'kk' => 'Ресурстар', 'en' => 'Resources'][$lang] }}</a>
        </div>
      </div>

      <aside class="contact-highlight">
        <span>{{ ['ru' => 'Справочная стойка', 'kk' => 'Анықтама қызметі', 'en' => 'Library desk'][$lang] }}</span>
        <strong>+7 (7172) 64-58-58</strong>
        <p>{{ ['ru' => 'Астана · ул. Кайыма Мухамедханова, 37A', 'kk' => 'Астана · Қайым Мұхамедханов көшесі, 37A', 'en' => 'Astana · 37A Kayym Mukhamedkhanov Street'][$lang] }}</p>
        <a href="mailto:library@digital-library.demo">library@digital-library.demo</a>
      </aside>
    </div>
  </section>

  <section class="page-section">
    <div class="container about-grid">
      <div class="contact-editorial">
        <div class="eyebrow">{{ $copy['mission_label'] }}</div>
        <h2 class="heading-xl">{{ $copy['mission_title'] }}</h2>
        <p class="text-body" style="margin: 0 0 18px;">{{ $copy['mission_body'] }}</p>
        <div class="contact-facts">
          <div><strong>{{ ['ru' => 'Более 50 000', 'kk' => '50 000+', 'en' => '50,000+'][$lang] }}</strong><span>{{ ['ru' => 'Единиц фонда', 'kk' => 'Қор бірліктері', 'en' => 'Collection items'][$lang] }}</span></div>
          <div><strong>24/7</strong><span>{{ ['ru' => 'Цифровой доступ', 'kk' => 'Цифрлық қолжетімділік', 'en' => 'Digital access'][$lang] }}</span></div>
          <div><strong>{{ ['ru' => 'Гибрид', 'kk' => 'Гибрид', 'en' => 'Hybrid'][$lang] }}</strong><span>{{ ['ru' => 'Печатный + цифровой', 'kk' => 'Баспа + цифрлық', 'en' => 'Print + digital'][$lang] }}</span></div>
        </div>
      </div>

      <div class="contact-stack">
        <article class="contact-strip">
          <span class="contact-strip-label">{{ ['ru' => 'Адрес', 'kk' => 'Мекенжай', 'en' => 'Address'][$lang] }}</span>
          <h3>{{ ['ru' => 'Информационно-библиотечный центр', 'kk' => 'Ақпараттық-кітапхана орталығы', 'en' => 'Information and library center'][$lang] }}</h3>
          <p>{{ ['ru' => 'Астана, ул. Кайыма Мухамедханова, 37A — очная помощь по выдаче, доступу и справочному сопровождению.', 'kk' => 'Астана, Қайым Мұхамедханов көшесі, 37A — беру, қолжетімділік және анықтамалық қолдау бойынша офлайн көмек.', 'en' => 'Astana, 37A Kayym Mukhamedkhanov Street — on-site help for circulation, access, and reference support.'][$lang] }}</p>
        </article>

        <article class="contact-strip">
          <span class="contact-strip-label">{{ ['ru' => 'Поддержка читателей', 'kk' => 'Оқырман қолдауы', 'en' => 'Reader support'][$lang] }}</span>
          <h3>{{ ['ru' => 'Позвоните или напишите в библиотеку', 'kk' => 'Кітапханаға қоңырау шалыңыз немесе жазыңыз', 'en' => 'Call or write to the library'][$lang] }}</h3>
          <p><a href="tel:+77172645858">+7 (7172) 64-58-58</a><br><a href="mailto:library@digital-library.demo">library@digital-library.demo</a></p>
        </article>

        <article class="contact-strip">
          <span class="contact-strip-label">{{ ['ru' => 'Онлайн-сервисы', 'kk' => 'Онлайн сервистер', 'en' => 'Online services'][$lang] }}</span>
          <h3>{{ ['ru' => 'Переход к рабочим разделам', 'kk' => 'Жұмыс бөлімдеріне өту', 'en' => 'Move directly into the working surfaces'][$lang] }}</h3>
          <p><a href="{{ $routeWithLang('/catalog') }}">{{ __('ui.nav.catalog') }}</a> · <a href="{{ $routeWithLang('/account') }}">{{ __('ui.nav.account') }}</a> · <a href="{{ $routeWithLang('/shortlist') }}">{{ __('ui.nav.shortlist') }}</a> · <a href="{{ $routeWithLang('/resources') }}">{{ __('ui.nav.resources') }}</a></p>
        </article>
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container contacts-grid">
      <div class="card contact-panel">
        <div class="eyebrow eyebrow--violet">{{ $copy['hours'] }}</div>
        <h2>{{ $copy['hours_help'] }}</h2>
        <ul class="info-list">
          <li><span class="icon">•</span><div><strong>{{ ['ru' => 'Понедельник – Пятница', 'kk' => 'Дүйсенбі – Жұма', 'en' => 'Monday – Friday'][$lang] }}</strong><br><span class="text-muted">09:00 – 18:00</span></div></li>
          <li><span class="icon">•</span><div><strong>{{ ['ru' => 'Суббота', 'kk' => 'Сенбі', 'en' => 'Saturday'][$lang] }}</strong><br><span class="text-muted">10:00 – 14:00</span></div></li>
          <li><span class="icon">•</span><div><strong>{{ ['ru' => 'Воскресенье', 'kk' => 'Жексенбі', 'en' => 'Sunday'][$lang] }}</strong><br><span class="text-muted">{{ ['ru' => 'Выходной', 'kk' => 'Демалыс', 'en' => 'Closed'][$lang] }}</span></div></li>
        </ul>
        <p class="text-body-sm" style="margin-top: 14px;">{{ ['ru' => 'Каталог и личный кабинет остаются доступными круглосуточно, даже когда физическая стойка закрыта.', 'kk' => 'Физикалық қызмет көрсету орны жабық кезде де каталог пен жеке кабинет тәулік бойы қолжетімді.', 'en' => 'The catalog and account portal remain available around the clock even when the physical desk is closed.'][$lang] }}</p>
      </div>

      <div class="card contact-panel">
        <div class="eyebrow eyebrow--green">{{ $copy['units'] }}</div>
        <h2>{{ $copy['units_help'] }}</h2>
        <ul class="info-list">
          <li><span class="icon">•</span><div><strong>{{ ['ru' => 'Абонемент', 'kk' => 'Абонемент', 'en' => 'Circulation'][$lang] }}</strong><br><span class="text-muted">{{ ['ru' => 'Выдача, возврат, регистрация читателей и физический доступ.', 'kk' => 'Берілім, қайтарым, оқырманды тіркеу және физикалық қолжетімділік.', 'en' => 'Loans, returns, reader registration, and physical access.'][$lang] }}</span></div></li>
          <li><span class="icon">•</span><div><strong>{{ ['ru' => 'Читальный зал', 'kk' => 'Оқу залы', 'en' => 'Reading room'][$lang] }}</strong><br><span class="text-muted">{{ ['ru' => 'Тихая работа со справочными материалами и текущей периодикой.', 'kk' => 'Анықтамалық материалдар мен мерзімді басылымдармен тыныш жұмыс.', 'en' => 'Quiet work with reference materials and current periodicals.'][$lang] }}</span></div></li>
          <li><span class="icon">•</span><div><strong>{{ ['ru' => 'Библиографическая поддержка', 'kk' => 'Библиографиялық қолдау', 'en' => 'Bibliographic support'][$lang] }}</strong><br><span class="text-muted">{{ ['ru' => 'Помощь с поиском, подбором источников и академическими ссылками.', 'kk' => 'Іздеу, дереккөз таңдау және академиялық сілтемелер бойынша көмек.', 'en' => 'Search help, source discovery, and academic reference guidance.'][$lang] }}</span></div></li>
          <li><span class="icon">•</span><div><strong>{{ ['ru' => 'Формирование фонда', 'kk' => 'Қорды дамыту', 'en' => 'Collection development'][$lang] }}</strong><br><span class="text-muted">{{ ['ru' => 'Комплектование, каталогизация и сопровождение фонда.', 'kk' => 'Жинақтау, каталогтау және қорды сүйемелдеу.', 'en' => 'Acquisitions, cataloging, and fund maintenance.'][$lang] }}</span></div></li>
        </ul>
      </div>
    </div>
  </section>
@endsection

@section('head')
<style>
  .contact-hero-shell {
    display: grid;
    grid-template-columns: 1.3fr 320px;
    gap: 20px;
    align-items: stretch;
    text-align: left;
    animation: contactReveal .45s cubic-bezier(0.2, 0.8, 0.2, 1) both;
  }

  .contact-hero-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 18px;
  }

  .contact-highlight {
    padding: 20px;
    border-radius: var(--radius-xl);
    background: linear-gradient(180deg, rgba(255,255,255,.98), rgba(243,244,245,.94));
    border: 1px solid var(--border);
    box-shadow: var(--shadow-soft);
    display: grid;
    gap: 8px;
    align-content: start;
    transition: transform .24s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .24s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .contact-highlight:hover {
    transform: translate3d(0, -2px, 0);
    box-shadow: 0 14px 28px rgba(25, 28, 29, 0.05);
    border-color: rgba(0,30,64,.12);
  }

  .contact-highlight span {
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .14em;
    color: var(--cyan);
  }

  .contact-highlight strong {
    font-family: 'Newsreader', Georgia, serif;
    font-size: 1.8rem;
    line-height: 1.1;
    color: var(--blue);
  }

  .contact-highlight p,
  .contact-highlight a {
    color: var(--muted);
    line-height: 1.7;
  }

  .about-grid {
    display: grid;
    grid-template-columns: 1.1fr 0.9fr;
    gap: 24px;
    align-items: start;
  }

  .contact-editorial {
    padding-right: 10px;
  }

  .contact-facts {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 10px;
  }

  .contact-facts div {
    padding: 14px;
    border-radius: var(--radius-lg);
    background: linear-gradient(180deg, rgba(255,255,255,.98), rgba(243,244,245,.94));
    border: 1px solid var(--border);
    transition: transform .22s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .22s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .contact-facts div:hover {
    transform: translate3d(0, -2px, 0);
    box-shadow: 0 12px 24px rgba(25, 28, 29, 0.04);
    border-color: rgba(20,105,109,.18);
  }

  .contact-facts strong {
    display: block;
    margin-bottom: 4px;
    font-size: 22px;
    color: var(--blue);
  }

  .contact-facts span {
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: var(--muted);
  }

  .contact-stack {
    display: grid;
    gap: 12px;
  }

  .contact-strip {
    padding: 18px;
    border-radius: var(--radius-lg);
    background: #fff;
    border: 1px solid var(--border);
    transition: transform .22s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .22s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .contact-strip:hover {
    transform: translate3d(0, -2px, 0);
    box-shadow: 0 12px 24px rgba(25, 28, 29, 0.04);
    border-color: rgba(0,30,64,.12);
  }

  .contact-strip-label {
    display: inline-block;
    margin-bottom: 6px;
    color: var(--cyan);
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .12em;
  }

  .contact-strip h3 {
    margin: 0 0 6px;
    font-family: 'Newsreader', Georgia, serif;
    color: var(--blue);
    font-size: 1.35rem;
  }

  .contact-strip p,
  .contact-strip a {
    margin: 0;
    color: var(--muted);
    line-height: 1.7;
  }

  .contacts-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    align-items: start;
  }

  .contact-panel {
    transition: transform .24s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .24s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .contact-panel:hover {
    transform: translate3d(0, -2px, 0);
    box-shadow: 0 14px 28px rgba(25, 28, 29, 0.05);
    border-color: rgba(20,105,109,.18);
  }

  .contact-panel h2 {
    margin: 0 0 10px;
    font-family: 'Newsreader', Georgia, serif;
    color: var(--blue);
    font-size: 1.6rem;
  }

  @media (max-width: 900px) {
    .contact-hero-shell,
    .about-grid,
    .contacts-grid {
      grid-template-columns: 1fr;
    }
  }

  @media (max-width: 680px) {
    .contact-facts {
      grid-template-columns: 1fr;
    }
  }

  @keyframes contactReveal {
    from {
      opacity: 0;
      transform: translate3d(0, 10px, 0);
    }

    to {
      opacity: 1;
      transform: translate3d(0, 0, 0);
    }
  }
</style>
@endsection
