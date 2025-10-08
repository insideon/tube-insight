<?php

namespace App\Filament\Resources\ContentPlans;

use App\Filament\Resources\ContentPlans\Pages\CreateContentPlan;
use App\Filament\Resources\ContentPlans\Pages\EditContentPlan;
use App\Filament\Resources\ContentPlans\Pages\ListContentPlans;
use App\Filament\Resources\ContentPlans\Schemas\ContentPlanForm;
use App\Filament\Resources\ContentPlans\Tables\ContentPlansTable;
use App\Models\ContentPlan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ContentPlanResource extends Resource
{
    protected static ?string $model = ContentPlan::class;

    protected static ?string $navigationLabel = '콘텐츠 기획안';

    protected static ?string $modelLabel = '콘텐츠 기획안';

    protected static ?string $pluralModelLabel = '콘텐츠 기획안';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ContentPlanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContentPlansTable::configure($table);
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
            'index' => ListContentPlans::route('/'),
            'create' => CreateContentPlan::route('/create'),
            'edit' => EditContentPlan::route('/{record}/edit'),
        ];
    }
}
