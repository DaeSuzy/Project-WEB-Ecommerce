<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductTransactionResource\Pages;
use App\Filament\Resources\ProductTransactionResource\RelationManagers;
use App\Models\Dompet;
use App\Models\ProductTransaction;
use App\Models\PromoCode;
use Filament\Forms;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Forms\FormsComponent;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductTransactionResource extends Resource
{
    protected static ?string $model = ProductTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([

                    Forms\Components\Wizard\Step::make('Product and Price')
                    ->schema([

                        Grid::make(2)
                        ->schema([
                             Forms\Components\Select::make('dompet_id')
                            ->relationship('dompet', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $get, callable $set){
                                $dompet = Dompet::find($state);
                                $price = $dompet ? $dompet->pricce : 0;
                                $quantity = $get('quantity') ?? 1;
                                $subTotalAmount = $price * $quantity;

                                $set('price', $price);
                                $set('sub_total_amount', $subTotalAmount);

                                $discount = $get('discount_amount') ?? 0;
                                $grandTotalAmount = $subTotalAmount - $discount;
                                $set('grand_total_amount', $grandTotalAmount);

                                $sizes = $dompet ? $dompet->sizes->pluck('size','id')->toArray() : [];
                                $set('dompet_sizes', $sizes);
                            })
                            ->afterStateHydrated(function($state, callable $get, callable $set){
                                $dompetId = $state;
                                if ($dompetId){
                                    $dompet = Dompet::find($dompetId);
                                    $sizes = $dompet ? $dompet->sizes->pluck('size', 'id')->toArray() : [];
                                    $set('dompet_sizes', $sizes);
                                }
                            }),

                            Forms\Components\Select::make('dompet_size')
                                ->label('Dompet Size')
                                ->options(function (callable $get){
                                    $sizes = $get ('dompet_sizes');
                                    return is_array($sizes) ? $sizes : [];
                                })
                                ->required()
                                ->live(),
                            
                            Forms\Components\TextInput::make('quantity')
                            ->required()
                            ->numeric()
                            ->prefix('Qty')
                            ->live()
                            ->afterStateUpdated(function($state, callable $get, callable $set){
                                $price = $get('price');
                                $quantity = $state;
                                $subTotalAmount = $price * $quantity;

                                $set('sub_total_amount', $subTotalAmount);

                                $discount = $get('discount_amount') ?? 0;
                                $grandTotalAmount = $subTotalAmount - $discount;
                                $set('grand_total_amount',$grandTotalAmount);
                            }),

                            Forms\Components\Select::make('promo_code_id')
                            ->relationship('promoCode', 'code')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $get, callable $set){
                                $subTotalAmount = $get('sub_total_amount');
                                $promoCode = PromoCode::find($state);
                                $discount = $promoCode ? $promoCode->discount_amount : 0;

                                $set('discount_amount', $discount);

                                $grandTotalAmount = $subTotalAmount - $discount;
                                $set('grand_total_amount', $grandTotalAmount);
                            }),

                            Forms\Components\TextInput::make('sub_total_amount')
                            ->required()
                            ->readOnly()
                            ->numeric()
                            ->prefix('IDR'),

                            Forms\Components\TextInput::make('grand_total_amount')
                            ->required()
                            ->readOnly()
                            ->numeric()
                            ->prefix('IDR'),

                            Forms\Components\TextInput::make('discount_amount')
                            ->required()
                            ->numeric()
                            ->prefix('IDR'),
                            
                        ]),
                    ]),

                    Forms\Components\Wizard\Step::make('Customer Information')
                        ->schema([

                            Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255),

                                Forms\Components\TextInput::make('phone')
                                ->required()
                                ->maxLength(255),

                                Forms\Components\TextInput::make('email')
                                ->required()
                                ->maxLength(255),

                                Forms\Components\TextArea::make('address')
                                ->rows(5)
                                ->required(),

                                Forms\Components\TextInput::make('city')
                                ->required()
                                ->maxLength(255),

                                Forms\Components\TextInput::make('post_Code')
                                ->required()
                                ->maxLength(255),
                            ]),
                        ]),


                        Forms\Components\Wizard\Step::make('Payment Information')
                        ->schema([

                        Forms\Components\TextInput::make('booking_trx_id')
                            ->required()
                            ->maxLength(255),

                            ToggleButtons::make('is_paid')
                            ->label('Apakah sudah membayar?')
                            ->boolean()
                            ->grouped()
                            ->icons([
                                true =>'heroicon-o-pencil',
                                false => 'heroicon-o-clock',
                            ])
                            ->required(),

                            Forms\Components\FileUpload::make('proof')
                                ->image()
                                ->required(),
                            ]),

                ])
                ->columnSpan('full')
                ->columns(1)
                ->skippable()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\ImageColumn::make('dompet.thumbnail'),

                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('booking_trx_id')
                    ->searchable(),
                
                Tables\Columns\IconColumn::make('is_paid')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->label('Terverifikasi'),
            ])
            ->filters([
                //
                SelectFilter::make('dompet_id')
                    ->label('dompet')
                    ->relationship('dompet','name')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->action(function(ProductTransaction $record){
                        $record->is_paid = true;
                        $record->save();

                        //
                        Notification::make()
                            ->title('Order Approved')
                            ->success()
                            ->body('The Order has been successfully approved.')
                            ->send();
                    })
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn(ProductTransaction $record) => !$record->is_paid),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductTransactions::route('/'),
            'create' => Pages\CreateProductTransaction::route('/create'),
            'edit' => Pages\EditProductTransaction::route('/{record}/edit'),
        ];
    }
}
