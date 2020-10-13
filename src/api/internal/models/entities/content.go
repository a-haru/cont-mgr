package entities

import "time"

type Content struct {
	ID          uint64 `gorm:"primaryKey"`
	ClientId    uint64 `gorm:index`
	Title       string
	Description string
	Text        string
	Note        string
	UpdateAt    time.Time
	CreatedAt   time.Time
	DeletedAt   time.Time
}
