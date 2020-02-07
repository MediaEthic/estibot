<!doctype html>
<html lang="fr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex">
        <title>Devis#{{ $quotation->id }}-{{ $quotation->updated_at }}-{{ $quotation->third['name'] }}</title>
        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <style>
            .page-break {
                page-break-after: always;
            }

            .small {
                font-size: 12px;
                line-height: 1.228;
            }

            table thead {
                text-transform: uppercase;
            }
        </style>
    </head>

    <body>
    <script type="text/php">
            if (isset($pdf)) {
                $x = 290;
                $y = 820;
                $text = "{PAGE_NUM} / {PAGE_COUNT}";
                $font = null;
                $size = 10;
                $color = array(0, 0, 0);
                $word_space = 0.0;  //  default
                $char_space = 0.0;  //  default
                $angle = 0.0;   //  default
                if ($PAGE_COUNT > 1) {
                    $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
                }
            }
        </script>

        <div>
            <div class="row">
                <div class="col-xs-3">
{{--                    <img src="{{ url('/assets/img/logo-ethic-software.png') }}"--}}
{{--                         alt="Logotype Ethic Software"--}}
{{--                         class="main-logo" />--}}
{{--                </div>--}}
                </div>
            </div>

            <div class="row border-bottom">
                <div class="col-xs-3">
                </div>
                <div class="col-xs-8">
                    <address class="small text-right">
                        <strong>{{ $quotation->company->name }}</strong>,
                        @isset($quotation->company->address_line1){{ $quotation->company->address_line1 }}, @endisset
                        @isset($quotation->company->address_line2){{ $quotation->company->address_line2 }}, @endisset
                        @isset($quotation->company->address_line3){{ $quotation->company->address_line3 }}, @endisset
                        {{ $quotation->third['zipcode'] }} {{ $quotation->third['city'] }}<br>
                        @isset($quotation->company->phone)<abbr title="Téléphone">Tél.</abbr> {{ $quotation->company->phone }} | @endisset
                        @isset($quotation->company->email)<a href="mailto:{{ $quotation->company->email }}" class="small">{{ $quotation->company->email }}</a>@endisset
                    </address>
                </div>
            </div>

            <div style="margin-bottom: 0px">&nbsp;</div>

            <div class="row">
                <div class="col-xs-4">
                    <table style="width: 100%">
                        <tbody>
                            <tr>
                                <th>Devis</th>
                                <td class="text-right">#{{ $quotation->id }}</td>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <td class="text-right">{{ date('d/m/Y', strtotime($quotation->updated_at)) }}</td>
                            </tr>
                            <tr>
                                <th>Fin de validité</th>
                                <td class="text-right">{{ date('d/m/Y', strtotime($quotation->validity)) }}</td>
                            </tr>
                            <tr>
                                <th>Suivi par</th>
                                <td class="text-right">{{ $quotation->user_name }} {{ $quotation->user_surname }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-xs-2">
                </div>

                <div class="col-xs-5">
                    <address>
                        <strong>{{ $quotation->third['name'] }}</strong><br>
                        @if(isset($quotation->contact_surname) || isset($quotation->contact_name))
                            @if(isset($quotation->contact_surname) || isset($quotation->contact_name))
                                À l'attention de
                                @if($quotation->contact_civility === "Mr")
                                    <abbr title="Monsieur">{{ $civility = "M." }}</abbr>
                                @else
                                    <abbr title="Madame">{{ $civility = "Mme" }}</abbr>
                                @endif
                                {{ $quotation->contact_surname }} {{ $quotation->contact_name }}<br>
{{--                            @else--}}
{{--                                @isset($quotation->contact->service)--}}
{{--                                    Service {{ $quotation->contact->service }}<br>--}}
{{--                                @endisset--}}
                            @endif
                        @endif
                        @isset($quotation->third['address_line1']){{ $quotation->third['address_line1'] }}<br>@endisset
                        @isset($quotation->third['address_line2']){{ $quotation->third['address_line2'] }}<br>@endisset
                        @isset($quotation->third['address_line3']){{ $quotation->third['address_line3'] }}<br>@endisset
                        {{ $quotation->third['zipcode'] }} {{ $quotation->third['city'] }}
                    </address>
                </div>
            </div>

            <div style="margin-bottom: 0px">&nbsp;</div>

            <p>{{ $quotation->company->head_quotation }}</p>

            <dl>
                @foreach(explode("\n", $quotation->description) as $description)
                    <dd>{{ $description }}</dd>
                @endforeach
            </dl>

            <div style="margin-bottom: 0px">&nbsp;</div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Expédition</th>
                        <th>Prix unitaire</th>
                        <th class="text-right">Montant HT</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quotation->quantities as $quantity)
                        <tr>
                            <td>
                                <strong>{{ $quantity->quantity }} exemplaires</strong><br>
                                <span>{{ $quantity->models }} modèle@php if($quantity->models > 1) echo "s" @endphp</span><br>
                                <span>{{ $quantity->plates }} cliché@if($quantity->plates > 1)s @endif</span><br>
                                @if($quantity->prepress > 0)
                                    <span>
                                    @php
                                        $hours = floor($quantity->prepress);
                                        $minutes = ($quantity->prepress - $hours) * 60;
                                        if ($hours > 0) {
                                            echo $hours . "h";
                                        }
                                        if ($minutes > 0) {
                                        echo $minutes;
                                        }
                                    @endphp
                                         de prépresse
                                    </span>
                                @endif
                            </td>
                            <td>{{ $quantity->shipping }}€</td>
                            <td>{{ round($quantity->subtotal / $quantity->quantity, 2) }}€</td>
                            <td class="text-right">{{ $quantity->subtotal }}€</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="row">
                <div class="col-xs-6">
{{--                    <h5>Termes et conditions</h5>--}}
{{--                    <p>{{ $quotation->company->gsc }}</p>--}}
                </div>
                <div class="col-xs-5">
                    <table style="width: 100%">
                        <tbody>
                            <tr style="padding: 5px">
                                <th style="padding: 5px"><div>Total HT</div></th>
                                <td style="padding: 5px" class="text-right"><strong>{{ round($quotation->cost + $quotation->shipping, 2) }}€</strong></td>
                            </tr>
                            <tr style="padding: 5px">
                                <th style="padding: 5px"><div>Total TVA (20%)</div></th>
                                <td style="padding: 5px" class="text-right"><strong>{{ $quotation->vat_price }}€</strong></td>
                            </tr>
                            <tr class="well" style="padding: 5px">
                                <th style="padding: 5px"><div>Total TTC</div></th>
                                <td style="padding: 5px" class="text-right"><strong>{{ $quotation->price }}€</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div style="margin-bottom: 0px">&nbsp;</div>

{{--            <div class="row">--}}
{{--                <div class="col-xs-12">--}}
{{--                    <p>{{ $quotation->company->foot_quotation }}</p>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="row" style="border-top: 1px solid #ddd;">--}}
{{--                <div class="col-xs-12">--}}
{{--                    <p class="text-center">{{ $quotation->company->siret }} | {{ $quotation->company->tva }}</p>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </body>
</html>
