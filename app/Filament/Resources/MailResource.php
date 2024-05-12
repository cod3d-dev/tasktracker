<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MailResource\Pages;
use App\Filament\Resources\MailResource\RelationManagers;
use App\Jobs\MailCheck;
use App\Models\Mail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MailResource extends Resource
{
    protected static ?string $model = Mail::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('message_id')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('date')
                    ->required(),
                Forms\Components\TextInput::make('from')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('subject')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('body')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull()
                    ->rows(10),

                Forms\Components\Toggle::make('processed')
                    ->required(),
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('from1')->default('test'),
                ]),
                Forms\Components\TextInput::make('Task Name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Actions::make([
                    Forms\Components\Actions\Action::make('test')->action(function (Set $set, $state) {
                        dump($state);
                    })
                ]),
            ]);
    }

    public static function ConvertToTask(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('message_id')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('date')
                    ->required(),
                Forms\Components\TextInput::make('subject')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('body')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('from')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('processed')
                    ->required(),
                Forms\Components\Textarea::make('headers')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
//        dispatch(new MailCheck);
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('from')
                    ->searchable(),
                Tables\Columns\TextColumn::make('message_id')->limit(10)->hidden(true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject')
                    ->searchable(),

                Tables\Columns\IconColumn::make('processed')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Process'),
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
            'index' => Pages\ListMails::route('/'),
            'create' => Pages\CreateMail::route('/create'),
            'edit' => Pages\EditMail::route('/{record}/edit'),
            'convert' => Pages\ConvertToTask::route('/{record}/convert'),
        ];
    }

}
