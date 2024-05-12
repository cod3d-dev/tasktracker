<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Filament\Resources\TaskResource\Widgets\CalendarWidget;
use App\Filament\Resources\TaskResource\Widgets\TasksOverview;
use App\Forms\Components\Timer;
use App\Models\Manager;
use App\Models\project;
use App\Models\Task;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rules\Unique;
use Livewire\Attributes\Layout;
use Illuminate\Database\Query\Builder as QueryBuilder;



class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';

    protected static array $statuses = [
        '0' => 'pending',
        '1' => 'completed',
        '2' => 'postponed'
    ];



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Task Information')
                    ->schema([
                        Select::make('project_id')->relationship('project', 'name')->required()->live()->options(Project::pluck('name', 'id'))->autofocus(),
                        Select::make('manager_id')
                            ->relationship('manager', 'name')
                            ->options(fn(Get $get) => Manager::where('project_id', $get('project_id'))->pluck('name', 'id'))
                            ->disabled(fn(Get $get) : bool => ! filled($get('project_id'))),
                        TextInput::make('description')->required()->autocomplete(false)->unique(ignoreRecord: true),
//                            ->searchable(),
//                            ->unique(modifyRuleUsing: function (Unique $rule, callable $get) {
//                            return $rule->where('project_id', $get('project_id'));
//                        }),
                        Select::make('type_id')->relationship('type', 'name')->required(),
                        DatePicker::make('posted_date')->default(today())->required()->closeOnDateSelection(),
                        DatePicker::make('due_date')->default(today())->required(),
                        TextInput::make('words')->autocomplete(false),
                        Textarea::make('notes')->rows(5)->columnSpan(2)->autocomplete(false),
                        Toggle::make('priority')->label('ASAP'),
                        TextInput::make('link')->url()->autocomplete(false)->required(true),
                        Radio::make('status')
                            ->options(self::$statuses)->default('0'),
                        Toggle::make('completed'),
                        TextInput::make('time_spent')->default( '0'),
                    ])->columns(2)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultGroup('due_date')
            ->defaultPaginationPageOption(25)
            ->paginated([25,50, 100, 'all'])
            ->groups([
                Group::make('project.name')->collapsible(),
                Group::make('due_date')->date()->collapsible()->label('Due Date'),
            ])
            ->recordClasses(fn (Task $record) => match ($record->priority) {
                1 => '!border-s-2 !border-amber-600 !bg-red-100',
                0 => '!border-s-2 !border-amber-600 !dark:border-amber-300 !dark:text-custom-400',
                default => 'border-s-2 border-green-600 dark:border-green-300',
            })
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\Layout\Grid::make([
                        'md' => 10
                    ])
                        ->schema([

                            Tables\Columns\Layout\Stack::make([
                                TextColumn::make('project.name')->sortable(),
                                TextColumn::make('manager.name')->sortable()->searchable()->size('xs')->limit(12),
                            ]),
                            Tables\Columns\Layout\Stack::make([
                                TextColumn::make('description')->wrap()->searchable()
                                    ->url(fn (Task $record): string => $record->link)->openUrlInNewTab()->sortable(),
                                TextColumn::make('type.name')->sortable('desc')->formatStateUsing(fn (string $state): string => ("Type: {$state}"))->size('xs'),
                                TextColumn::make('posted_date')->dateTime('d/m/Y')->label('Posted')->sortable()->size('xs')->formatStateUsing(fn (string $state): string => ("Posted: {$state}"))->color('info'),
                            ])->columnSpan(['md' => 4]),




                            TextColumn::make('words')->sortable()->formatStateUsing(fn (string $state): string => ("Words<br>{$state}"))->html()
                                ->summarize([
                                Sum::make()->label('Total Words'),
                                Sum::make()->query(fn (QueryBuilder $query) => $query->where('completed', 1))->label('Completed'),
                                Sum::make()->query(fn (QueryBuilder $query) => $query->where('completed', 0))->label('To do'),
                                ])
                            ,
                            TextColumn::make('notes')->state(function (Task $record): string {
                                if($record->notes != null) {
                                    return 'Notes';
                                }else {
                                    return '';
                                }

                            })->badge(),
                            TextColumn::make('priority')->state(function (Task $record): string {
                                if ($record->priority == 0) {
                                    return 'Normal';
                                } else  {
                                    return 'ASAP';
                                }
                            })->badge()
                                ->color(function (Task $record): string {
                                    if ($record->priority == 1) {
                                        return 'danger';
                                    } else  {
                                        return 'success';
                                    }
                                }),

                            Tables\Columns\IconColumn::make('completed')->boolean(),
                            TextColumn::make('time_spent')->numeric(decimalPlaces: 0)->summarize(Sum::make()),

                        ]),




                ]),
                Tables\Columns\Layout\Panel::make([
                            TextColumn::make('notes'),

                ])->collapsible(true),

//                TextColumn::make('type.name')->sortable('desc'),




//                TextColumn::make('due_date')->dateTime('d/m/Y')->sortable('desc'),


//                Tables\Columns\SelectColumn::make('status')->options(self::$statuses)->toggleable(isToggledHiddenByDefault: true),
//
            ])
            ->filters([
                Tables\Filters\Filter::make('Due')
                    ->query(function (Builder $query): Builder {
                    return $query
                        ->where('due_date', '<=', today())->where('completed', 0);

                })->default(),
                Tables\Filters\TernaryFilter::make('completed')
                    ->queries(
                        true: fn (Builder $query) => $query->where('completed', 1),
                        false: fn (Builder $query) => $query->where('completed', 0),
                    ),
                Tables\Filters\SelectFilter::make('project_id')
                    ->relationship('project', 'name'),
                Tables\Filters\SelectFilter::make('status')
                    ->options(self::$statuses),
                Tables\Filters\SelectFilter::make('type_id')
                    ->relationship('type', 'name'),
                Tables\Filters\Filter::make('due_date')
                    ->form([
                        Forms\Components\DatePicker::make('due_from')->label('Due date start:')->default(now()),
                        Forms\Components\DatePicker::make('due_until')->label('Due date end:')->default(now()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['due_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('due_date', ">=", $date),
                            )
                            ->when(
                                $data['due_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('due_date', "<=", $date),
                            );
                    }),

//                Tables\Filters\Filter::make('due_date')->label('Today')->default()
//                    ->form([
//                        Forms\Components\DatePicker::make('due')->default(now()),
//                    ])
//                    ->query(function (Builder $query, array $data): Builder {
//                        return $query
//                            ->when(
//                                $data['due'],
//                                fn (Builder $query, $date): Builder => $query->whereDate('due_date', '=', $date),
//                            );
//                    })
            ], layout: Tables\Enums\FiltersLayout::AboveContentCollapsible)

            ->actions([
                Tables\Actions\ActionGroup::make([

                    Tables\Actions\EditAction::make()
                        ->slideOver(),
                    Tables\Actions\Action::make('complete')
                        ->icon('heroicon-o-check-circle')
                        ->action(function (Task $record) {
                            $record->status = 1;
                            $record->completed = true;
                            $record->save();
                        })
                        ->form([
                            TextInput::make('time_spent')->default( '0')->label('Time Spent')->required(),
                        ]),
                    Tables\Actions\DeleteAction::make()->requiresConfirmation(),
                ])

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([

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
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
//            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];

    }

    public static function getWidgets(): array
    {
        return [
          TasksOverview::class,
//          CalendarWidget::class
        ];
    }

//    protected function getHeaderWidgets(): array {
//        return
//        [
//            TasksOverview::class,
//        ];
//    }


}
