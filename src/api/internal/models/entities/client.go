package entities

import (
	"time"
)

type Client struct {
	ID                   uint64 `gorm:"primaryKey"`
	Name                 string
	Url                  string
	ContractActivateAt   time.Time
	ContractDeactivateAt time.Time
	UpdateAt             time.Time
	CreatedAt            time.Time
	DeletedAt            time.Time
}
